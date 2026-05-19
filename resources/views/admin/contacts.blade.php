@extends('admin.layout')

@section('title', 'Contact Messages')
@section('page_header', 'Inbox - Contact Messages')

@section('content')
<div class="container-fluid p-0 animate-fade-in">

    @if(session('success'))
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success alert-dismissible fade show mb-4" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fs-5">Inbound Messages</span>
            <span class="badge bg-secondary">{{ $contacts->total() }} total messages</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Sender Name</th>
                        <th>Email Address</th>
                        <th>Message Content</th>
                        <th>Status</th>
                        <th>Received Date</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td class="text-secondary">{{ $loop->iteration + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                            <td class="fw-semibold text-white">{{ $contact->name }}</td>
                            <td>
                                <a href="mailto:{{ $contact->email }}" class="text-primary text-decoration-none">
                                    {{ $contact->email }}
                                </a>
                            </td>
                            <td class="text-secondary" style="max-width: 400px; word-break: break-word;" title="{{ $contact->message }}">
                                {{ Str::limit($contact->message, 120) }}
                            </td>
                            <td>
                                @if($contact->is_read)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1">Read</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1">New</span>
                                @endif
                            </td>
                            <td class="text-secondary font-monospace">{{ $contact->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="text-end">
                                @if(!$contact->is_read)
                                    <form action="{{ route('admin.contacts.read', $contact->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success px-3">Mark Read</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary px-3" disabled>Read</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No messages found in your inbox.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($contacts->hasPages())
            <div class="card-footer d-flex justify-content-center py-3">
                {{ $contacts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
