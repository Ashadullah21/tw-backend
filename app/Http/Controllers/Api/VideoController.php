<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtractVideoRequest;
use App\Services\ActivityLoggerService;
use App\Services\YtDlpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Constructor injecting both extraction and logging services.
     */
    public function __construct(
        private readonly YtDlpService $ytDlpService,
        private readonly ActivityLoggerService $activityLoggerService
    ) {}

    /**
     * Extract video information from a Twitter/X URL.
     *
     * POST /api/extract
     */
    public function extract(ExtractVideoRequest $request): JsonResponse
    {
        $url       = $request->validated('url');
        $ip        = $request->ip();
        $userAgent = $request->userAgent();
        $referer   = $request->header('referer', '');

        try {
            // Delegate extraction to the service layer
            $videoInfo = $this->ytDlpService->extract($url);

            // Log successful extraction with details and summaries via the ActivityLoggerService
            $this->activityLoggerService->log(
                ip: $ip,
                url: $url,
                status: 'success',
                userAgent: $userAgent,
                referer: $referer
            );

            return response()->json($videoInfo, 200);

        } catch (\RuntimeException $e) {
            // Log failed extraction with details and summaries via the ActivityLoggerService
            $this->activityLoggerService->log(
                ip: $ip,
                url: $url,
                status: 'failed',
                userAgent: $userAgent,
                referer: $referer
            );

            return response()->json([
                'error'   => 'Extraction failed',
                'message' => 'Failed to extract video. Please ensure the URL is valid, public, and contains a video.',
            ], 422);

        } catch (\Exception $e) {
            // Catch-all for unexpected errors
            $this->activityLoggerService->log(
                ip: $ip,
                url: $url,
                status: 'failed',
                userAgent: $userAgent,
                referer: $referer
            );

            return response()->json([
                'error'   => 'Internal server error',
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Proxy and stream external video URLs to force an instant browser download dialog.
     * Optionally transcode to MP3 audio-only on the fly if format=mp3 is passed.
     *
     * GET /api/download?url=xxx&title=yyy&format=mp4|mp3
     */
    public function download(Request $request)
    {
        $url    = $request->query('url');
        $title  = $request->query('title', 'twitter-video');
        $format = strtolower($request->query('format', 'mp4'));

        if (empty($url)) {
            abort(400, 'Missing url parameter');
        }

        // Clean filename (strip extension if present and append appropriate extension)
        $filename = pathinfo($title, PATHINFO_FILENAME);
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);

        if ($format === 'mp3') {
            $filename = ($filename ?: 'audio') . '.mp3';
            
            // Set headers for audio download
            $headers = [
                'Content-Type'        => 'audio/mpeg',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response()->streamDownload(function () use ($url) {
                // Use ffmpeg to stream from the MP4 URL and transcode to MP3 on the fly.
                // -loglevel quiet hides ffmpeg terminal output from the stream.
                // -vn disables video stream.
                // -acodec libmp3lame encodes the audio to MP3 format.
                // -f mp3 specifies output format.
                // - redirects stdout.
                $escapedUrl = escapeshellarg($url);
                $command = "ffmpeg -loglevel quiet -i {$escapedUrl} -vn -acodec libmp3lame -f mp3 -";

                $stream = popen($command, 'r');

                if ($stream) {
                    while (!feof($stream)) {
                        echo fread($stream, 1024 * 8); // Stream in 8KB chunks
                        flush();
                    }
                    pclose($stream);
                }
            }, $filename, $headers);

        } else {
            $filename = ($filename ?: 'video') . '.mp4';

            // Set headers for video download
            $headers = [
                'Content-Type'        => 'video/mp4',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response()->streamDownload(function () use ($url) {
                $context = stream_context_create([
                    'http' => [
                        'header'          => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
                        'follow_location' => true,
                        'max_redirects'   => 5,
                    ],
                    'ssl' => [
                        'verify_peer'      => false,
                        'verify_peer_name' => false,
                    ]
                ]);

                $stream = fopen($url, 'r', false, $context);

                if ($stream) {
                    while (!feof($stream)) {
                        echo fread($stream, 1024 * 8); // Stream in 8KB chunks
                        flush();
                    }
                    fclose($stream);
                }
            }, $filename, $headers);
        }
    }
}
