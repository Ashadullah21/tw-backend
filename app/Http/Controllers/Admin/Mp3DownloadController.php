<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mp3Download;
use App\Traits\CanExportCsv;
use Illuminate\Http\Request;

class Mp3DownloadController extends Controller
{
    use CanExportCsv;

    /**
     * Display a listing of MP3 downloads.
     *
     * GET /admin/mp3-downloads
     */
    public function index()
    {
        // Dynamic entries per page selection (whitelisted choices for safety)
        $perPage = request()->integer('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        $search = request('search');
        $query = Mp3Download::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('referer', 'like', "%{$search}%");
            });
        }

        // Paginate MP3 downloads, latest first
        $mp3Downloads = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.mp3_downloads.index', compact('mp3Downloads'));
    }

    /**
     * Export MP3 downloads log to CSV.
     *
     * GET /admin/export/mp3-downloads
     */
    public function export()
    {
        $search = request('search');
        $query = Mp3Download::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('referer', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        $filename = 'mp3_downloads_' . date('Y-m-d_H-i') . '.csv';
        $headers = ['# ID', 'Audio Title', 'Source URL', 'Client IP', 'User Agent', 'Referer', 'Downloaded At'];

        return $this->streamCsvExport($filename, $headers, $query, function ($row) {
            return [
                $row->id,
                $row->title,
                $row->url,
                $row->ip_address,
                $row->user_agent,
                $row->referer ?: 'Direct',
                $row->created_at,
            ];
        });
    }
}
