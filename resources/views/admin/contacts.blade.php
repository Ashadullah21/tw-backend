@extends('admin.layout')

@section('title', 'Contact Messages')
@section('page_header', 'Inbox Support')

@section('content')
<div class="container-fluid p-0 overflow-hidden">

    @if(session('success'))
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success alert-dismissible fade show mb-4 p-4 rounded-4 d-flex align-items-center justify-content-between" role="alert" style="border: 1px solid rgba(25, 135, 84, 0.2) !important;">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span><strong>Success!</strong> {{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white shadow-none m-0" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <span class="fs-5 fw-bold">Inbound Support Mailbox</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1.5" style="background-color: rgba(255, 255, 255, 0.08) !important; color: #a5b4fc; border: 1px solid rgba(255, 255, 255, 0.05);">{{ $contacts->total() }} total messages</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Sender Name</th>
                        <th>Email Address</th>
                        <th>Message Content</th>
                        <th>Status</th>
                        <th>Received Date</th>
                        <th class="text-end" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td class="fw-semibold" style="color: rgba(255, 255, 255, 0.45) !important;">{{ $loop->iteration + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                            <td class="fw-semibold text-white">{{ $contact->name }}</td>
                            <td>
                                <a href="mailto:{{ $contact->email }}" class="text-primary text-decoration-none fw-semibold" style="color: var(--brand-primary) !important;">
                                    {{ $contact->email }}
                                </a>
                            </td>
                            <td class="text-secondary" style="max-width: 400px; word-break: break-word;" title="{{ $contact->message }}">
                                {{ Str::limit($contact->message, 120) }}
                            </td>
                            <td>
                                @if($contact->is_read)
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1.5" style="font-size: 0.75rem; font-weight: 700;">
                                        <span class="d-inline-block rounded-circle bg-success me-1.5" style="width: 6px; height: 6px; box-shadow: 0 0 6px #198754;"></span>Read
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-3 py-1.5" style="font-size: 0.75rem; font-weight: 700; color: #ffb300 !important; border-color: rgba(255, 179, 0, 0.2) !important;">
                                        <span class="d-inline-block rounded-circle bg-warning me-1.5" style="width: 6px; height: 6px; box-shadow: 0 0 6px #ffb300; background-color: #ffb300 !important;"></span>New
                                    </span>
                                @endif
                            </td>
                            <td class="text-secondary font-monospace small">{{ $contact->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="text-end">
                                @if(!$contact->is_read)
                                    <form action="{{ route('admin.contacts.read', $contact->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm px-3 py-1.5 fw-bold" style="background-color: rgba(25, 135, 84, 0.1); border: 1px solid rgba(25, 135, 84, 0.3); color: #198754; border-radius: 8px; font-size: 0.8rem; transition: all 0.2s ease;">
                                            Mark Read
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm px-3 py-1.5 border-0 fw-bold" style="background-color: rgba(255, 255, 255, 0.05); border-radius: 8px; font-size: 0.8rem; color: rgba(255, 255, 255, 0.45) !important;" disabled>
                                        Processed
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-inbox mb-2 text-muted opacity-50" viewBox="0 0 16 16">
                                    <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0 .5.5 0 0 1 .5-.5h4.46L11.41 4.19A.5.5 0 0 0 11.02 4H4.98zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .11.313v4.5A1.5 1.5 0 0 1 14.5 14h-13A1.5 1.5 0 0 1 0 12.5v-4.5a.5.5 0 0 1 .11-.313l3.7-4.625z"/>
                                </svg>
                                <div>No support requests found in your inbox.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($contacts->hasPages())
            <div class="card-footer d-flex justify-content-center py-4 bg-transparent border-0">
                {{ $contacts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
