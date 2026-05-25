<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Traits\CanExportCsv;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use CanExportCsv;

    /**
     * Display a listing of the contact messages.
     *
     * GET /admin/contacts
     */
    public function index()
    {
        // Dynamic entries per page selection (whitelisted choices for safety)
        $perPage = request()->integer('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        $search = request('search');
        $status = request('status');

        $query = ContactMessage::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status === 'read') {
            $query->where('is_read', true);
        } elseif ($status === 'unread') {
            $query->where('is_read', false);
        }

        $contacts = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.contacts', compact('contacts'));
    }

    /**
     * Mark a single contact message as read.
     *
     * POST /admin/contacts/{id}/read
     */
    public function markRead(int $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Export contacts log to CSV.
     *
     * GET /admin/export/contacts
     */
    public function export()
    {
        $search = request('search');
        $status = request('status');

        $query = ContactMessage::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status === 'read') {
            $query->where('is_read', true);
        } elseif ($status === 'unread') {
            $query->where('is_read', false);
        }

        $query->orderBy('created_at', 'desc');

        $filename = 'contacts_' . date('Y-m-d_H-i') . '.csv';
        $headers = ['# ID', 'Sender Name', 'Email Address', 'Message', 'Status', 'Received At'];

        return $this->streamCsvExport($filename, $headers, $query, function ($row) {
            return [
                $row->id,
                $row->name,
                $row->email,
                $row->message,
                $row->is_read ? 'Read' : 'Unread',
                $row->created_at,
            ];
        });
    }
}
