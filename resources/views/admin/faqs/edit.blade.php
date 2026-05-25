@extends('admin.layout')

@section('title', 'Edit FAQ')
@section('page_header', 'Modify Frequently Asked Question')

@section('content')
<div class="container-fluid p-0 overflow-hidden" style="max-width: 800px;">

    <!-- ── Back to list button ── -->
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-ghost mb-4 text-decoration-none d-inline-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
        </svg>
        <span>Back to FAQs</span>
    </a>

    <!-- ── Form Card ── -->
    <div class="card border-0 p-4 shadow-lg">
        <div class="card-header bg-transparent border-0 p-0 mb-4">
            <span class="fs-5 fw-extrabold text-white">Modify FAQ Details</span>
            <p class="small text-secondary mb-0">Modify the question text, detailed response, and active order weight.</p>
        </div>

        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
            @csrf

            <!-- Question -->
            <div class="mb-4">
                <label for="question" class="form-label fw-bold text-white small text-uppercase" style="letter-spacing: 0.08em; color: var(--text-secondary) !important;">Question Title</label>
                <input 
                    type="text" 
                    name="question" 
                    id="question" 
                    class="form-control form-control-lg text-white @error('question') is-invalid @enderror" 
                    style="background-color: var(--bg-input, #1a2340); border: 1px solid var(--border-subtle); border-radius: 12px;"
                    placeholder="e.g. How many videos can I download in a day?" 
                    value="{{ old('question', $faq->question) }}" 
                    required
                >
                @error('question')
                    <div class="invalid-feedback fw-semibold mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Answer -->
            <div class="mb-4">
                <label for="answer" class="form-label fw-bold text-white small text-uppercase" style="letter-spacing: 0.08em; color: var(--text-secondary) !important;">Answer Explanation</label>
                <textarea 
                    name="answer" 
                    id="answer" 
                    rows="6" 
                    class="form-control text-white @error('answer') is-invalid @enderror" 
                    style="background-color: var(--bg-input, #1a2340); border: 1px solid var(--border-subtle); border-radius: 12px; resize: vertical;"
                    placeholder="Provide a detailed, helpful answer explaining this point..." 
                    required
                >{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')
                    <div class="invalid-feedback fw-semibold mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Display Order -->
            <div class="mb-4" style="max-width: 250px;">
                <label for="order" class="form-label fw-bold text-white small text-uppercase" style="letter-spacing: 0.08em; color: var(--text-secondary) !important;">Display Sequence Order</label>
                <input 
                    type="number" 
                    name="order" 
                    id="order" 
                    class="form-control text-white @error('order') is-invalid @enderror" 
                    style="background-color: var(--bg-input, #1a2340); border: 1px solid var(--border-subtle); border-radius: 12px;"
                    value="{{ old('order', $faq->order) }}" 
                    min="0"
                >
                <div class="form-text text-secondary mt-1 small">Lower numbers appear first. Default is 0.</div>
                @error('order')
                    <div class="invalid-feedback fw-semibold mt-2">{{ $message }}</div>
                @enderror
            </div>

            <hr class="border-subtle my-4">

            <!-- Actions -->
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-ghost px-4 py-2.5 rounded-3 fw-bold">Cancel</a>
                <button type="submit" class="btn px-4 py-2.5 rounded-3 fw-bold" style="background: linear-gradient(135deg, var(--brand-primary), #1570cf); color: white; border: none; box-shadow: 0 4px 15px rgba(29, 155, 240, 0.25);">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
