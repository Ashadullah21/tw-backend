<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ActivityLoggerService
{
    /**
     * Log a download request in download_logs and upsert the matching summary in user_activity_summary.
     */
    public function log(string $ip, string $url, string $status, ?string $userAgent, ?string $referer): void
    {
        // 1. Insert the detailed request row in download_logs
        DB::table('download_logs')->insert([
            'url'        => $url,
            'ip_address' => $ip,
            'status'     => $status,
            'user_agent' => $userAgent ? substr($userAgent, 0, 500) : null,
            'referer'    => $referer ? substr($referer, 0, 500) : null,
            'created_at' => now(),
        ]);

        // 2. Fetch the existing summary record for the matching IP address
        $summary = DB::table('user_activity_summary')->where('ip_address', $ip)->first();

        if ($summary) {
            // Update the existing record safely using php-level incrementing
            DB::table('user_activity_summary')
                ->where('ip_address', $ip)
                ->update([
                    'total_requests' => $summary->total_requests + 1,
                    'total_success'  => $status === 'success' ? $summary->total_success + 1 : $summary->total_success,
                    'total_failed'   => $status === 'failed' ? $summary->total_failed + 1 : $summary->total_failed,
                    'last_seen_at'   => now(),
                    'updated_at'     => now(),
                ]);
        } else {
            // Insert the first summary record for this IP address
            DB::table('user_activity_summary')->insert([
                'ip_address'     => $ip,
                'total_requests' => 1,
                'total_success'  => $status === 'success' ? 1 : 0,
                'total_failed'   => $status === 'failed' ? 1 : 0,
                'last_seen_at'   => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
