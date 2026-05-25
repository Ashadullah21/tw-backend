<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Traits\CanExportCsv;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use CanExportCsv;

    /**
     * Display a listing of the FAQs.
     *
     * GET /admin/faqs
     */
    public function index()
    {
        // Dynamic entries per page selection (whitelisted choices for safety)
        $perPage = request()->integer('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        $search = request('search');
        $query = Faq::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $faqs = $query->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ.
     *
     * GET /admin/faqs/create
     */
    public function create()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created FAQ in storage.
     *
     * POST /admin/faqs
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer'   => $validated['answer'],
            'order'    => $validated['order'] ?? 0,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    /**
     * Show the form for editing the specified FAQ.
     *
     * GET /admin/faqs/{id}/edit
     */
    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ in storage.
     *
     * POST /admin/faqs/{id}
     */
    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
        ]);

        $faq->update([
            'question' => $validated['question'],
            'answer'   => $validated['answer'],
            'order'    => $validated['order'] ?? 0,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified FAQ from storage.
     *
     * POST /admin/faqs/{id}/delete
     */
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Public API endpoint to get all FAQs.
     *
     * GET /api/faqs
     */
    public function apiIndex()
    {
        $faqs = Faq::orderBy('order')->orderBy('created_at', 'desc')->get();
        return response()->json($faqs);
    }

    /**
     * Export FAQs log to CSV.
     *
     * GET /admin/export/faqs
     */
    public function export()
    {
        $search = request('search');
        $query = Faq::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $query->orderBy('order')->orderBy('created_at', 'desc');

        $filename = 'faqs_' . date('Y-m-d_H-i') . '.csv';
        $headers = ['# ID', 'Question', 'Answer', 'Sort Order', 'Created At', 'Updated At'];

        return $this->streamCsvExport($filename, $headers, $query, function ($row) {
            return [
                $row->id,
                $row->question,
                $row->answer,
                $row->order,
                $row->created_at,
                $row->updated_at,
            ];
        });
    }
}
