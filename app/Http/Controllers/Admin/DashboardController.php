<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\UserActivitySummary;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * GET /admin/dashboard
     */
    public function index()
    {
        // Paginate download logs, latest first (20 per page)
        $downloadLogs = DB::table('download_logs')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get top 20 rows from user_activity_summary ordered by total_requests descending
        $userActivities = UserActivitySummary::orderBy('total_requests', 'desc')
            ->limit(20)
            ->get();

        // Get count of unread messages
        $unreadMessagesCount = ContactMessage::where('is_read', false)->count();

        // Pass to admin dashboard view
        return view('admin.dashboard', compact('downloadLogs', 'userActivities', 'unreadMessagesCount'));
    }
}
