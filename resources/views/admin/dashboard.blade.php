@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_header', 'System Monitoring')

@section('content')
<div class="container-fluid p-0 overflow-hidden">

    <!-- ── Stats Overview Counters ─────────────────────────────────── -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 p-4 h-100" style="background: linear-gradient(135deg, rgba(29, 155, 240, 0.15), rgba(29, 155, 240, 0.03)) !important; box-shadow: 0 4px 20px rgba(29, 155, 240, 0.05);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="small text-uppercase fw-bold mb-0" style="letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.55) !important;">Unread Mailbox</p>
                    <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.03 6.862L9.75 8.161 8 9.21 6.25 8.161.03 12.145A1 1 0 0 0 1 13h14a1 1 0 0 0 .97-.855zM1 11.114l4.758-2.876L1 5.483v5.631z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="mb-1 fw-extrabold text-white" style="letter-spacing: -0.5px;">{{ $unreadMessagesCount }}</h3>
                <p class="small text-secondary mb-0">Unread contact messages in support inbox</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 p-4 h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.03)) !important; box-shadow: 0 4px 20px rgba(16, 185, 129, 0.05);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="small text-uppercase fw-bold mb-0" style="letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.55) !important;">System Health</p>
                    <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="mb-1 fw-extrabold text-success" style="letter-spacing: -0.5px;">100%</h3>
                <p class="small text-secondary mb-0">All crawler nodes fully operational</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 p-4 h-100" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.03)) !important; box-shadow: 0 4px 20px rgba(99, 102, 241, 0.05);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <p class="small text-uppercase fw-bold mb-0" style="letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.55) !important;">Server Environment</p>
                    <div class="p-2 rounded-3 bg-indigo bg-opacity-10 text-indigo" style="color: #818cf8 !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5V5h1.5A1.5 1.5 0 0 1 10 6.5V9h3.5A1.5 1.5 0 0 1 15 10.5V13h.5a.5.5 0 0 1 0 1h-1.5a.5.5 0 0 1 0-1H14v-2.5a.5.5 0 0 0-.5-.5H10v-1.5A1.5 1.5 0 0 1 8.5 7H7V2.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5V5H2v7.5a.5.5 0 0 0 .5.5H3v1.5A1.5 1.5 0 0 1 1.5 16h-1a.5.5 0 0 1 0-1H1v-1.5a.5.5 0 0 0-.5-.5H0a.5.5 0 0 1 0-1h.5V10.5A1.5 1.5 0 0 1 2 9h1.5V6.5a1.5 1.5 0 0 1 1.5-1.5H7v-1.5h-.5a.5.5 0 0 1 0-1H7v-.5H5.5a.5.5 0 0 1 0-1H7v-.5H2.5A1.5 1.5 0 0 1 1 2.5z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="mb-1 fw-extrabold text-white" style="letter-spacing: -0.5px; font-size: 1.25rem;">Laravel v{{ app()->version() }}</h3>
                <p class="small text-secondary mb-0">PHP v{{ phpversion() }} on Render cluster</p>
            </div>
        </div>
    </div>

    <!-- ── SECTION A: Download Logs ────────────────────────────────── -->
    <div class="card mb-5">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <span class="fs-5 fw-bold">Detailed Extraction Logs (Paginated)</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1.5" style="background-color: rgba(255, 255, 255, 0.08) !important; color: #a5b4fc; border: 1px solid rgba(255, 255, 255, 0.05);">{{ $downloadLogs->total() }} total entries</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Target Tweet URL</th>
                        <th>IP Address</th>
                        <th>Extraction Status</th>
                        <th>User Agent (Device Info)</th>
                        <th>Referer</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($downloadLogs as $log)
                        <tr>
                            <td class="fw-semibold" style="color: rgba(255, 255, 255, 0.45) !important;">{{ $loop->iteration + ($downloadLogs->currentPage() - 1) * $downloadLogs->perPage() }}</td>
                            <td>
                                <a href="{{ $log->url }}" target="_blank" class="text-primary text-decoration-none fw-semibold" title="{{ $log->url }}" style="color: var(--brand-primary) !important;">
                                    {{ Str::limit($log->url, 45) }}
                                </a>
                            </td>
                            <td><span class="font-monospace text-light fw-medium">{{ $log->ip_address }}</span></td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1.5" style="box-shadow: 0 0 10px rgba(25, 135, 84, 0.08); font-size: 0.75rem; font-weight: 700;">
                                        <span class="d-inline-block rounded-circle bg-success me-1.5" style="width: 6px; height: 6px; box-shadow: 0 0 6px #198754;"></span>Success
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1.5" style="box-shadow: 0 0 10px rgba(220, 53, 69, 0.08); font-size: 0.75rem; font-weight: 700;">
                                        <span class="d-inline-block rounded-circle bg-danger me-1.5" style="width: 6px; height: 6px; box-shadow: 0 0 6px #dc3545;"></span>Failed
                                    </span>
                                @endif
                            </td>
                            <td class="text-secondary small" title="{{ $log->user_agent }}">
                                {{ $log->user_agent ? Str::limit($log->user_agent, 40) : 'N/A' }}
                            </td>
                            <td class="text-secondary small" title="{{ $log->referer }}">
                                {{ $log->referer ? Str::limit($log->referer, 25) : 'Direct' }}
                            </td>
                            <td class="text-secondary font-monospace small">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-folder2-open mb-2 text-muted opacity-50" viewBox="0 0 16 16">
                                    <path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v7a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 12.5v-9zM2.5 3a.5.5 0 0 0-.5.5V6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.374 3.334 5.82 3 5.264 3H2.5zM14 7H2v5.5a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5V7z"/>
                                </svg>
                                <div>No extraction logs found in database.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($downloadLogs->hasPages())
            <div class="card-footer d-flex justify-content-center py-4 bg-transparent border-0">
                {{ $downloadLogs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- ── SECTION B: User Activity Summary ────────────────────────── -->
    <div class="card">
        <div class="card-header">
            <span class="fs-5 fw-bold">Top 20 User Activity Profiles (by Requests)</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Client IP Address</th>
                        <th>Total Requests</th>
                        <th>Successful Runs</th>
                        <th>Failed Runs</th>
                        <th>Last Seen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($userActivities as $activity)
                        <tr>
                            <td><span class="font-monospace fw-bold text-white">{{ $activity->ip_address }}</span></td>
                            <td class="fw-bold text-light">{{ $activity->total_requests }}</td>
                            <td>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-3 py-1">
                                    ✓ {{ $activity->total_success }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 px-3 py-1">
                                    ✗ {{ $activity->total_failed }}
                                </span>
                            </td>
                            <td class="text-secondary font-monospace small">
                                {{ $activity->last_seen_at ? $activity->last_seen_at->format('Y-m-d H:i:s') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people mb-2 text-muted opacity-50" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.274.51-1.063 1.91-1.124.96-.041 1.542.211 1.761.348.067.042.109.088.13.118a.31.31 0 0 1 .054.162h-3.833zm-.008-.944A.753.753 0 0 1 8 11c1.685 0 2.611.8 2.862 1.487h.001A.25.25 0 0 1 10.621 13H5.378a.25.25 0 0 1-.241-.313c.25-.688 1.177-1.488 2.861-1.488zm-3.64-1.002A1.996 1.996 0 0 1 5 10c0-.853.256-1.579.645-2.072.395-.5.952-.828 1.53-.928.9-.156 1.718.17 2.11.458.118.087.21.177.275.253.076.088.118.163.131.205a.25.25 0 0 1-.161.313c-.05.016-.098.026-.145.031a.243.243 0 0 1-.173-.046c-.054-.038-.119-.089-.193-.146-.358-.277-.925-.522-1.637-.4a1.005 1.005 0 0 0-.766.72C6.012 8.784 6 9.034 6 9.25v.748a.25.25 0 0 1-.25.25h-.378zm4.721-6.19a.25.25 0 0 1-.249.25h-.5a.25.25 0 0 1-.25-.25v-.5a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5zm-2 0a.25.25 0 0 1-.249.25h-.5a.25.25 0 0 1-.25-.25v-.5a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5zm2.75 3.328c.174-.188.358-.33.522-.433.24-.15.485-.226.708-.226.544 0 .918.423.918 1.009v1.5a.25.25 0 0 1-.25.25h-.378a.25.25 0 0 1-.25-.25v-.748c0-.216-.012-.466-.062-.647a1.005 1.005 0 0 0-.766-.72 1.996 1.996 0 0 0-1.637.4c-.074.057-.139.108-.193.146a.243.243 0 0 1-.173.046c-.047-.005-.095-.015-.145-.031a.25.25 0 0 1-.161-.313c.013-.042.055-.117.131-.205.065-.076.157-.166.275-.253.392-.288 1.21-.614 2.11-.458.578.1 1.135.428 1.53.928.389.493.645 1.219.645 2.072 0 .853-.256 1.579-.645 2.072-.395.5-.952.828-1.53.928-.9.156-1.718-.17-2.11-.458-.118-.087-.21-.177-.275-.253-.076-.088-.118-.163-.131-.205a.25.25 0 0 1 .161-.313c.05-.016.098-.026.145-.031a.243.243 0 0 1 .173.046c.054.038.119.089.193.146.358.277.925.522 1.637.4a1.005 1.005 0 0 0 .766-.72c.05-.181.062-.431.062-.647v-.748a.25.25 0 0 1 .25-.25h.378z"/>
                                </svg>
                                <div>No user activity profile data accumulated yet.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
