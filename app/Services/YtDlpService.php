<?php

namespace App\Services;

use RuntimeException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $cacheKey = 'tweet_video_' . md5($url);

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($url) {
            // 1. Try ultra-fast Syndication API first
            $syndicationResult = $this->extractViaSyndication($url);
            if ($syndicationResult !== null) {
                return $syndicationResult;
            }

            // 2. Fall back to slow but stable yt-dlp execution
            Log::info("Syndication API unavailable. Falling back to yt-dlp for URL: {$url}");

            $jsonOutput = $this->runYtDlp($url);
            $data       = $this->parseJson($jsonOutput);

            return [
                'title'     => $this->extractTitle($data),
                'thumbnail' => $this->extractThumbnail($data),
                'qualities' => $this->extractQualities($data),
            ];
        });
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

    /**
     * Extract the Tweet ID from a Twitter/X URL.
     */
    private function getTweetId(string $url): ?string
    {
        if (preg_match('/status\/(\d+)/i', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Calculate Twitter Syndication API V8-precision base-36 token.
     */
    private function getSyndicationToken(string $tweetId): string
    {
        $val = ((double)$tweetId / 1e15) * M_PI;
        
        $intPart = (int)floor($val);
        $fracPart = $val - $intPart;

        // Convert integer part to base 36
        $intStr = base_convert((string)$intPart, 10, 36);

        // Count significant digits in integer part
        $sigDigits = 0;
        if ($intPart > 0) {
            $sigDigits = strlen($intStr);
        }

        $fracStr = '';
        // We want a total of 11 significant digits
        $targetSig = 11;
        $limit = $targetSig - $sigDigits;

        // Generate base-36 digits for the fractional part
        while ($fracPart > 0 && strlen($fracStr) < $limit) {
            $fracPart *= 36;
            $digit = (int)floor($fracPart);
            $fracStr .= base_convert((string)$digit, 10, 36);
            $fracPart -= $digit;
        }

        // Combine like JS toString(36)
        $tokenRaw = $intPart > 0 ? $intStr . '.' . $fracStr : '0.' . $fracStr;
        
        // Remove periods and zeros
        return preg_replace('/(0+|\.)/', '', $tokenRaw);
    }

    /**
     * Attempt to extract video metadata via the Twitter Syndication API.
     */
    private function extractViaSyndication(string $url): ?array
    {
        $tweetId = $this->getTweetId($url);
        if (!$tweetId) {
            return null;
        }

        try {
            $token = $this->getSyndicationToken($tweetId);
            $apiUrl = "https://cdn.syndication.twimg.com/tweet-result?id={$tweetId}&token={$token}";
            
            // Call Twitter Syndication API with a modern User-Agent
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'application/json',
            ])->timeout(5)->get($apiUrl);

            if (!$response->successful()) {
                Log::warning("Twitter Syndication API failed for Tweet ID: {$tweetId}. Status: " . $response->status());
                return null;
            }

            $data = $response->json();
            if (empty($data)) {
                return null;
            }

            // Find video or animated gif in mediaDetails
            $mediaDetails = $data['mediaDetails'] ?? [];
            $videoMedia = null;

            foreach ($mediaDetails as $media) {
                $type = $media['type'] ?? '';
                if (($type === 'video' || $type === 'animated_gif') && isset($media['video_info'])) {
                    $videoMedia = $media;
                    break;
                }
            }

            if (!$videoMedia) {
                Log::info("No video media found in syndication data for Tweet ID: {$tweetId}");
                return null;
            }

            $title = $data['text'] ?? 'Twitter Video';
            // Clean up titles (sometimes Twitter appends the t.co URL at the end of text)
            $title = preg_replace('/https:\/\/t\.co\/\w+$/i', '', $title);
            $title = trim($title) ?: 'Twitter Video';

            $thumbnail = $videoMedia['media_url_https'] ?? '';

            // Extract variants
            $variants = $videoMedia['video_info']['variants'] ?? [];
            $qualities = [];

            // Filter for MP4 streams
            $mp4Variants = [];
            foreach ($variants as $variant) {
                if (($variant['content_type'] ?? '') === 'video/mp4' && !empty($variant['url'])) {
                    $mp4Variants[] = $variant;
                }
            }

            if (empty($mp4Variants)) {
                return null;
            }

            // Parse heights and map them
            $byHeight = [];
            foreach ($mp4Variants as $variant) {
                $vUrl = $variant['url'];
                $height = null;

                // Match width x height in URL (e.g. /vid/720x1280/file.mp4)
                if (preg_match('/(\d+)x(\d+)/', $vUrl, $resMatches)) {
                    $height = min((int)$resMatches[1], (int)$resMatches[2]);
                }

                // If not found in URL, look for bitrate as a heuristic
                if ($height === null && isset($variant['bitrate'])) {
                    $bitrate = (int)$variant['bitrate'];
                    if ($bitrate >= 2000000) {
                        $height = 720;
                    } elseif ($bitrate >= 800000) {
                        $height = 480;
                    } else {
                        $height = 360;
                    }
                }

                // Default if still null
                if ($height === null) {
                    $height = 360;
                }

                if ($height < self::MIN_HEIGHT) {
                    continue;
                }

                // Save or overwrite if higher bitrate
                $existing = $byHeight[$height] ?? null;
                if ($existing === null || ($variant['bitrate'] ?? 0) > ($existing['bitrate'] ?? 0)) {
                    $byHeight[$height] = $variant;
                }
            }

            if (empty($byHeight)) {
                return null;
            }

            // Build qualities aligned to QUALITY_LADDER
            foreach (self::QUALITY_LADDER as $targetHeight) {
                if (isset($byHeight[$targetHeight])) {
                    $format = $byHeight[$targetHeight];
                } else {
                    // Find closest available height that is <= target
                    $candidates = array_filter(
                        array_keys($byHeight),
                        fn(int $h) => $h <= $targetHeight
                    );

                    if (empty($candidates)) {
                        continue;
                    }

                    $closestHeight = max($candidates);
                    $format = $byHeight[$closestHeight];
                }

                $label = ($format['height'] ?? $targetHeight) . 'p';

                // We can extract height from the format or default it
                if (preg_match('/(\d+)x(\d+)/', $format['url'], $resMatches)) {
                    $label = min((int)$resMatches[1], (int)$resMatches[2]) . 'p';
                }

                $alreadyAdded = array_column($qualities, 'label');
                if (in_array($label, $alreadyAdded, strict: true)) {
                    continue;
                }

                $qualities[] = [
                    'label' => $label,
                    'url'   => $format['url'],
                    'ext'   => 'mp4',
                ];
            }

            // Fallback: if empty, take best
            if (empty($qualities)) {
                krsort($byHeight);
                $format = reset($byHeight);
                
                $label = 'Bestp';
                if (preg_match('/(\d+)x(\d+)/', $format['url'], $resMatches)) {
                    $label = min((int)$resMatches[1], (int)$resMatches[2]) . 'p';
                }

                $qualities[] = [
                    'label' => $label,
                    'url'   => $format['url'],
                    'ext'   => 'mp4',
                ];
            }

            return [
                'title'     => $title,
                'thumbnail' => $thumbnail,
                'qualities' => $qualities,
            ];

        } catch (\Exception $e) {
            Log::error("Error in extractViaSyndication for URL {$url}: " . $e->getMessage());
            return null;
        }
    }
}
