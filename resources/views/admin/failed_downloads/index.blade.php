@extends('admin.layout')

@section('title', 'Failed Attempts')
@section('page_header', 'Failed Attempts Tracker')

@section('content')
<div class="container-fluid p-0 overflow-hidden">

    <!-- ── Stats Overview ─────────────────────────────────────────── -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 p-4" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.03)) !important; box-shadow: 0 4px 20px rgba(239, 68, 68, 0.05);">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="small text-uppercase fw-bold mb-0" style="letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.55) !important;">Failure Tracking Logs</p>
                    <div class="p-2 rounded-3 bg-danger bg-opacity-10 text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="mb-1 fw-extrabold text-white" style="letter-spacing: -0.5px;">{{ $failedDownloads->total() }} Tracked Failures</h3>
                <p class="small text-secondary mb-0">Detailed catalog tracking why video extraction requests failed (e.g. invalid URL status, copyright blocks, guest tokens, private content)</p>
            </div>
        </div>
    </div>

    <!-- ── SECTION: Failure Log Table ───────────────────────────── -->
    <div class="card mb-5">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <span class="fs-5 fw-bold">Failed Video Extraction Logs</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1.5" style="background-color: rgba(255, 255, 255, 0.08) !important; color: #fca5a5; border: 1px solid rgba(255, 255, 255, 0.05);">{{ $failedDownloads->total() }} total entries</span>
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
                        <input type="text" name="search" class="form-control text-white" placeholder="Search by URL, Client IP or failure reason..." value="{{ request('search') }}">
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
                    <a href="{{ route('admin.export.failed_downloads', request()->query()) }}" class="btn btn-export px-3 fw-bold d-inline-flex align-items-center gap-1.5">
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
                        <th style="width: 70px;">#</th>
                        <th>Target Video URL</th>
                        <th>Client IP Address</th>
                        <th>Specific Exception Reason</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedDownloads as $log)
                        <tr>
                            <td class="fw-semibold" style="color: rgba(255, 255, 255, 0.45) !important;">{{ $loop->iteration + ($failedDownloads->currentPage() - 1) * $failedDownloads->perPage() }}</td>
                            <td>
                                <a href="{{ $log->url }}" target="_blank" class="text-primary text-decoration-none fw-semibold" title="{{ $log->url }}" style="color: var(--brand-primary) !important;">
                                    {{ Str::limit($log->url, 50) }}
                                </a>
                            </td>
                            <td><span class="font-monospace text-light fw-medium">{{ $log->ip_address ?: 'N/A' }}</span></td>
                            <td>
                                <div class="p-2 rounded-3 bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 small" style="max-width: 450px; white-space: normal; line-height: 1.4;">
                                    <strong class="d-block mb-0.5 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em; opacity: 0.8;">Failure Log:</strong>
                                    {{ $log->reason ?: 'Unknown error occurred during yt-dlp/Syndication API parsing.' }}
                                </div>
                            </td>
                            <td class="text-secondary font-monospace small">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-shield-check mb-2 text-muted opacity-50" viewBox="0 0 16 16">
                                    <path d="M8 14.5c-3.837-.47-6.5-3.376-6.5-7V4.02L8 1.41l6.5 2.61V7.5c0 3.624-2.663 6.53-6.5 7zM8 0a.5.5 0 0 0-.252.067L1.248 2.88a.5.5 0 0 0-.248.405V7.5c0 4.148 3.12 7.5 7 7.5s7-3.352 7-7.5V3.285a.5.5 0 0 0-.248-.405L8.252.067A.5.5 0 0 0 8 0z"/>
                                    <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                <div>No failed download attempts recorded. Excellent stability!</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($failedDownloads->isNotEmpty())
            <div class="card-footer d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-4 bg-transparent border-0">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Entries per page selector -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-secondary">Show</span>
                        <select onchange="window.location.href = this.value;" class="form-select form-select-sm text-white" style="width: auto; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; font-weight: 600; padding: 4px 28px 4px 12px; cursor: pointer;">
                            @foreach([10, 20, 50, 100] as $size)
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ $failedDownloads->perPage() == $size ? 'selected' : '' }} style="background-color: var(--card-bg); color: var(--text-primary);">
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        <span class="small text-secondary">entries</span>
                    </div>
                    
                    <!-- Total count stats -->
                    <span class="small text-secondary">
                        Showing <span class="fw-semibold text-white">{{ $failedDownloads->firstItem() ?? 0 }}</span> to 
                        <span class="fw-semibold text-white">{{ $failedDownloads->lastItem() ?? 0 }}</span> of 
                        <span class="fw-semibold text-white">{{ $failedDownloads->total() }}</span> entries
                    </span>
                </div>

                <!-- Page navigations -->
                @if($failedDownloads->hasPages())
                    <div class="d-flex justify-content-center m-0">
                        {{ $failedDownloads->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection
