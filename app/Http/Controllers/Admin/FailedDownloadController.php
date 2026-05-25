<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedDownload;
use App\Traits\CanExportCsv;
use Illuminate\Http\Request;

class FailedDownloadController extends Controller
{
    use CanExportCsv;

    /**
     * Display a listing of failed downloads.
     *
     * GET /admin/failed-downloads
     */
    public function index()
    {
        // Dynamic entries per page selection (whitelisted choices for safety)
        $perPage = request()->integer('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        $search = request('search');
        $query = FailedDownload::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        // Paginate failed downloads, latest first
        $failedDownloads = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.failed_downloads.index', compact('failedDownloads'));
    }

    /**
     * Export failed downloads log to CSV.
     *
     * GET /admin/export/failed-downloads
     */
    public function export()
    {
        $search = request('search');
        $query = FailedDownload::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        $filename = 'failed_downloads_' . date('Y-m-d_H-i') . '.csv';
        $headers = ['# ID', 'Target Video URL', 'Client IP', 'Failure Reason', 'Failed At'];

        return $this->streamCsvExport($filename, $headers, $query, function ($row) {
            return [
                $row->id,
                $row->url,
                $row->ip_address,
                $row->reason,
                $row->created_at,
            ];
        });
    }
}
