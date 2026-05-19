<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Display a listing of the contact messages.
     *
     * GET /admin/contacts
     */
    public function index()
    {
        // Paginate all contact messages (20 per page), latest first
        $contacts = ContactMessage::orderBy('created_at', 'desc')->paginate(20);

        // Mark all unread messages as read automatically upon viewing the list
        ContactMessage::where('is_read', false)->update(['is_read' => true]);

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
}
