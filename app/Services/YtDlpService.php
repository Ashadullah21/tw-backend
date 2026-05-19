<?php

namespace App\Services;

use RuntimeException;

class YtDlpService
{
    /**
     * Minimum video height to include in quality options.
     */
    private const MIN_HEIGHT = 360;

    /**
     * Desired quality ladder (height in pixels).
     */
    private const QUALITY_LADDER = [720, 480, 360];

    /**
     * Extract video metadata from a Twitter/X URL using yt-dlp.
     *
     * @param  string $url  A valid twitter.com or x.com video URL.
     * @return array{title: string, thumbnail: string, qualities: list<array{label: string, url: string, ext: string}>}
     *
     * @throws RuntimeException  When yt-dlp fails or the output cannot be parsed.
     */
    public function extract(string $url): array
    {
        $jsonOutput = $this->runYtDlp($url);
        $data       = $this->parseJson($jsonOutput);

        return [
            'title'     => $this->extractTitle($data),
            'thumbnail' => $this->extractThumbnail($data),
            'qualities' => $this->extractQualities($data),
        ];
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Run the yt-dlp command and return its stdout output.
     *
     * @throws RuntimeException  When the command produces no output.
     */
    private function runYtDlp(string $url): string
    {
        // Sanitize the URL to prevent shell injection
        $escapedUrl = escapeshellarg($url);

        // Check if cookies.txt exists in storage/app/cookies.txt to bypass Twitter guest blocks
        $cookiesPath = storage_path('app/cookies.txt');
        $cookiesArg  = '';

        if (file_exists($cookiesPath)) {
            $cookiesArg = ' --cookies ' . escapeshellarg($cookiesPath);
        }

        // Build the command:
        //   --dump-json          → output video metadata as JSON (no download)
        //   --no-playlist        → skip playlist expansion
        //   --no-warnings        → suppress non-critical warnings on stderr
        //   2>&1                 → merge stderr into stdout so we can detect errors
        $command = "yt-dlp --dump-json --no-playlist --no-warnings{$cookiesArg} {$escapedUrl} 2>&1";

        $output = shell_exec($command);

        if (empty($output)) {
            throw new RuntimeException(
                'yt-dlp returned no output. The video may be private, deleted, or unavailable.'
            );
        }

        return $output;
    }

    /**
     * Decode JSON output from yt-dlp.
     *
     * @throws RuntimeException  When the JSON is invalid or contains an error message.
     */
    private function parseJson(string $raw): array
    {
        // yt-dlp writes a single JSON object per line; grab the last non-empty line
        $lines = array_filter(
            array_map('trim', explode("\n", $raw)),
            fn(string $line) => str_starts_with($line, '{')
        );

        if (empty($lines)) {
            // yt-dlp printed an error instead of JSON
            throw new RuntimeException(
                'Could not extract video information. ' . trim($raw)
            );
        }

        $jsonLine = end($lines);
        $data     = json_decode($jsonLine, associative: true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to parse yt-dlp output as JSON.');
        }

        return $data;
    }

    /**
     * Extract the video title from yt-dlp data.
     */
    private function extractTitle(array $data): string
    {
        return $data['title'] ?? $data['fulltitle'] ?? 'Twitter Video';
    }

    /**
     * Extract the best available thumbnail URL.
     */
    private function extractThumbnail(array $data): string
    {
        // Prefer the highest-resolution thumbnail from the list
        $thumbnails = $data['thumbnails'] ?? [];

        if (!empty($thumbnails)) {
            // yt-dlp lists thumbnails from lowest to highest resolution
            $best = end($thumbnails);
            if (!empty($best['url'])) {
                return $best['url'];
            }
        }

        return $data['thumbnail'] ?? '';
    }

    /**
     * Extract and filter video quality options.
     *
     * Rules:
     *   - Format must have a direct URL (no manifest-only entries)
     *   - Extension must be mp4
     *   - Height must be >= MIN_HEIGHT
     *   - Prefer video-only formats; fall back to combined audio+video
     *
     * @return list<array{label: string, url: string, ext: string}>
     */
    private function extractQualities(array $data): array
    {
        $formats = $data['formats'] ?? [];

        // Build a map of height → best format for that height
        $byHeight = [];

        foreach ($formats as $format) {
            $height = $format['height'] ?? null;
            $ext    = $format['ext']    ?? '';
            $url    = $format['url']    ?? '';
            $proto  = $format['protocol'] ?? '';

            // Skip entries without a usable direct URL, wrong extension, or HLS manifest (.m3u8)
            if (empty($url) || $ext !== 'mp4' || $height === null || str_contains($url, '.m3u8') || str_contains($proto, 'm3u8')) {
                continue;
            }

            // Skip heights below our minimum
            if ($height < self::MIN_HEIGHT) {
                continue;
            }

            // Prefer video-only (vcodec set, acodec 'none'); fall back to combined
            $existing = $byHeight[$height] ?? null;

            if ($existing === null) {
                $byHeight[$height] = $format;
            } else {
                // Prefer video-only streams over combined streams
                $isVideoOnly = ($format['acodec'] ?? '') === 'none' &&
                               !empty($format['vcodec'])            &&
                               $format['vcodec'] !== 'none';

                if ($isVideoOnly) {
                    $byHeight[$height] = $format;
                }
            }
        }

        // Build quality array, aligned to our desired quality ladder
        $qualities = [];

        foreach (self::QUALITY_LADDER as $targetHeight) {
            // Look for an exact match first, then find the closest available
            if (isset($byHeight[$targetHeight])) {
                $format = $byHeight[$targetHeight];
            } else {
                // Find the closest height that is <= target
                $candidates = array_filter(
                    array_keys($byHeight),
                    fn(int $h) => $h <= $targetHeight
                );

                if (empty($candidates)) {
                    continue;
                }

                $closestHeight = max($candidates);
                $format        = $byHeight[$closestHeight];
            }

            $label = ($format['height'] ?? $targetHeight) . 'p';

            // Avoid duplicate labels
            $alreadyAdded = array_column($qualities, 'label');
            if (in_array($label, $alreadyAdded, strict: true)) {
                continue;
            }

            $qualities[] = [
                'label' => $label,
                'url'   => $format['url'],
                'ext'   => $format['ext'],
            ];
        }

        // Fallback: if no formats matched our ladder, return the best available
        if (empty($qualities) && !empty($byHeight)) {
            krsort($byHeight); // highest first
            $format = reset($byHeight);

            $qualities[] = [
                'label' => ($format['height'] ?? 'Best') . 'p',
                'url'   => $format['url'],
                'ext'   => $format['ext'],
            ];
        }

        if (empty($qualities)) {
            throw new RuntimeException(
                'No downloadable MP4 video formats were found for this URL. ' .
                'The tweet may contain only images or a GIF.'
            );
        }

        return $qualities;
    }
}
