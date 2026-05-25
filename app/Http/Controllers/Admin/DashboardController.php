<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\UserActivitySummary;
use App\Traits\CanExportCsv;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use CanExportCsv;

    /**
     * Display the admin dashboard.
     *
     * GET /admin/dashboard
     */
    public function index()
    {
        // Dynamic entries per page selection (whitelisted choices for safety)
        $perPage = request()->integer('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        // 1. Process Detailed Extraction Logs with search & filters
        $search = request('search');
        $status = request('status');

        $query = DB::table('download_logs');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhere('referer', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['success', 'failed'])) {
            $query->where('status', $status);
        }

        $downloadLogs = $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'logs_page')
            ->withQueryString();

        // 2. Process User Activity Summary with search
        $activitySearch = request('activity_search');
        $activityQuery = UserActivitySummary::query();

        if (!empty($activitySearch)) {
            $activityQuery->where('ip_address', 'like', "%{$activitySearch}%");
        }

        $userActivities = $activityQuery->orderBy('total_requests', 'desc')
            ->limit(20)
            ->get();

        // Get count of unread messages
        $unreadMessagesCount = ContactMessage::where('is_read', false)->count();

        // Success and failed downloads (extraction counts)
        $successDownloadsCount = DB::table('download_logs')->where('status', 'success')->count();
        $failedDownloadsCount  = DB::table('download_logs')->where('status', 'failed')->count();

        // Pass to admin dashboard view
        return view('admin.dashboard', compact(
            'downloadLogs', 
            'userActivities', 
            'unreadMessagesCount', 
            'successDownloadsCount', 
            'failedDownloadsCount'
        ));
    }

    /**
     * Export detailed extraction logs to CSV.
     *
     * GET /admin/export/download-logs
     */
    public function export()
    {
        $search = request('search');
        $status = request('status');

        $query = DB::table('download_logs');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhere('referer', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['success', 'failed'])) {
            $query->where('status', $status);
        }

        $query->orderBy('created_at', 'desc');

        $filename = 'extraction_logs_' . date('Y-m-d_H-i') . '.csv';
        $headers = ['# ID', 'Target Tweet URL', 'Client IP', 'Status', 'User Agent', 'Referer', 'Date & Time'];

        return $this->streamCsvExport($filename, $headers, $query, function ($row) {
            return [
                $row->id,
                $row->url,
                $row->ip_address,
                ucfirst($row->status),
                $row->user_agent,
                $row->referer ?: 'Direct',
                $row->created_at,
            ];
        });
    }

    /**
     * Export user activity summaries to CSV.
     *
     * GET /admin/export/user-activities
     */
    public function exportUserActivities()
    {
        $search = request('activity_search');

        $query = UserActivitySummary::query();

        if (!empty($search)) {
            $query->where('ip_address', 'like', "%{$search}%");
        }

        $query->orderBy('total_requests', 'desc');

        $filename = 'user_activities_' . date('Y-m-d_H-i') . '.csv';
        $headers = ['Client IP', 'Total Requests', 'Successful Runs', 'Failed Runs', 'Last Seen'];

        return $this->streamCsvExport($filename, $headers, $query, function ($row) {
            return [
                $row->ip_address,
                $row->total_requests,
                $row->total_success,
                $row->total_failed,
                $row->last_seen_at ? $row->last_seen_at->format('Y-m-d H:i:s') : 'N/A',
            ];
        });
    }
}
