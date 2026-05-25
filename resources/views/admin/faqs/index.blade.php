@extends('admin.layout')

@section('title', 'Manage FAQs')
@section('page_header', 'Frequently Asked Questions')

@section('content')
<div class="container-fluid p-0 overflow-hidden">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4" role="alert" style="background: rgba(0, 200, 83, 0.15); color: #00c853; box-shadow: 0 4px 15px rgba(0, 200, 83, 0.05);">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ── Header Action Card ────────────────────────────────────── -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 p-4" style="background: linear-gradient(135deg, rgba(29, 155, 240, 0.15), rgba(29, 155, 240, 0.03)) !important; box-shadow: 0 4px 20px rgba(29, 155, 240, 0.05);">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <h3 class="mb-1 fw-extrabold text-white" style="letter-spacing: -0.5px;">FAQ Administration</h3>
                        <p class="small text-secondary mb-0">Define, edit, and order dynamic questions & answers rendered directly on the site landing page.</p>
                    </div>
                    <a href="{{ route('admin.faqs.create') }}" class="btn px-4 py-2.5 rounded-3 fw-bold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, var(--brand-primary), #1570cf); color: white; border: none; box-shadow: 0 4px 15px rgba(29, 155, 240, 0.25);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        <span>Add New FAQ</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ── SECTION: FAQ Listing Table ───────────────────────────── -->
    <!-- ── SECTION: FAQ Listing Table ───────────────────────────── -->
    <div class="card shadow-lg mb-5">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <span class="fs-5 fw-bold">Active FAQs (Ordered)</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1.5" style="background-color: rgba(255, 255, 255, 0.08) !important; color: #a5b4fc; border: 1px solid rgba(255, 255, 255, 0.05);">{{ $faqs->total() }} total FAQs</span>
        </div>

        <!-- ── Search & Filter Controls ── -->
        <div class="p-4 border-bottom border-subtle" style="background-color: rgba(255, 255, 255, 0.005);">
            <form method="GET" action="" class="row g-3 align-items-center">
                <!-- Search field -->
                <div class="col-12 col-md-7 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </span>
                        <input type="text" name="search" class="form-control text-white" placeholder="Search by question or answer..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Entries Per Page -->
                <div class="col-12 col-sm-6 col-md-5 col-lg-3">
                    <select name="per_page" class="form-select text-white" onchange="this.form.submit();" style="cursor: pointer;">
                        @foreach([10, 20, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 20) == $size ? 'selected' : '' }}>{{ $size }} entries</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 col-md-12 col-lg-3 d-flex gap-2 justify-content-lg-end justify-content-start align-items-center flex-wrap mt-md-3 mt-lg-0">
                    <button type="submit" class="btn btn-outline-primary px-3 fw-bold">Apply</button>
                    @if(request()->has('search'))
                        <a href="{{ request()->url() }}" class="btn btn-ghost px-3">Reset</a>
                    @endif
                    <a href="{{ route('admin.export.faqs', request()->query()) }}" class="btn btn-export px-3 fw-bold d-inline-flex align-items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        <span>Export CSV</span>
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 100px;" class="text-center">Order</th>
                        <th>Question</th>
                        <th>Answer Summary</th>
                        <th>Created At</th>
                        <th style="width: 180px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td class="fw-bold font-monospace text-center text-primary" style="font-size: 1.05rem;">{{ $faq->order }}</td>
                            <td class="text-white fw-semibold" style="max-width: 250px; white-space: normal;">{{ $faq->question }}</td>
                            <td class="text-secondary small" style="max-width: 350px; white-space: normal;">
                                {{ Str::limit($faq->answer, 120) }}
                            </td>
                            <td class="text-secondary font-monospace small">{{ $faq->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-2 fw-semibold" style="border-color: rgba(29, 155, 240, 0.4); color: var(--brand-primary);">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete this FAQ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-2 fw-semibold">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-patch-question mb-2 text-muted opacity-50" viewBox="0 0 16 16">
                                    <path d="M5.002 6a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 3.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 15a.5.5 0 1 1-1 0c0-.253.125-.478.3-.6l.1-.05c.175-.1.3-.277.3-.475 0-.416-.3-.725-.667-.725a.723.723 0 0 0-.666.425.25.25 0 0 1-.46-.176C6.54 11.233 7.152 11 8 11c1 0 1.667.625 1.667 1.48 0 .444-.24.814-.582 1.05-.183.126-.3.308-.3.5a.5.5 0 0 1-.3.45z"/>
                                </svg>
                                <div>No FAQs configured yet. Click "Add New FAQ" to create one.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-4 bg-transparent border-0">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-secondary">Show</span>
                    <select onchange="window.location.href = this.value;" class="form-select form-select-sm text-white" style="width: auto; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; font-weight: 600; padding: 4px 28px 4px 12px; cursor: pointer;">
                        @foreach([10, 20, 50, 100] as $size)
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ $faqs->perPage() == $size ? 'selected' : '' }} style="background-color: var(--card-bg); color: var(--text-primary);">
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    <span class="small text-secondary">entries</span>
                </div>
                <span class="small text-secondary">
                    Showing <span class="fw-semibold text-white">{{ $faqs->firstItem() ?? 0 }}</span> to 
                    <span class="fw-semibold text-white">{{ $faqs->lastItem() ?? 0 }}</span> of 
                    <span class="fw-semibold text-white">{{ $faqs->total() }}</span> entries
                </span>
            </div>
            @if($faqs->hasPages())
                <div class="d-flex justify-content-center m-0">
                    {{ $faqs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
