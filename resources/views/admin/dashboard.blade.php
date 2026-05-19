@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_header', 'System Monitoring Dashboard')

@section('content')
<div class="container-fluid p-0">

    <!-- ── Stats Overview Counters ─────────────────────────────────── -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 bg-primary bg-gradient text-white">
                <p class="small text-uppercase fw-bold opacity-75 mb-1">Unread Mailbox</p>
                <h3 class="mb-0 fw-bold">{{ $unreadMessagesCount }} unread messages</h3>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 bg-success bg-gradient text-white">
                <p class="small text-uppercase fw-bold opacity-75 mb-1">System Health</p>
                <h3 class="mb-0 fw-bold">100% Operational</h3>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 bg-dark bg-gradient text-white">
                <p class="small text-uppercase fw-bold opacity-75 mb-1">Server Environment</p>
                <h3 class="mb-0 fw-bold">Laravel v{{ app()->version() }} (PHP v{{ phpversion() }})</h3>
            </div>
        </div>
    </div>

    <!-- ── SECTION A: Download Logs ────────────────────────────────── -->
    <div class="card shadow-lg mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fs-5">Detailed Extraction Logs (Paginated)</span>
            <span class="badge bg-secondary">{{ $downloadLogs->total() }} total entries</span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
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
                            <td class="text-secondary">{{ $loop->iteration + ($downloadLogs->currentPage() - 1) * $downloadLogs->perPage() }}</td>
                            <td>
                                <a href="{{ $log->url }}" target="_blank" class="text-primary text-decoration-none" title="{{ $log->url }}">
                                    {{ Str::limit($log->url, 50) }}
                                </a>
                            </td>
                            <td><span class="font-monospace">{{ $log->ip_address }}</span></td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1">Success</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1">Failed</span>
                                @endif
                            </td>
                            <td class="text-secondary small" title="{{ $log->user_agent }}">
                                {{ $log->user_agent ? Str::limit($log->user_agent, 45) : 'N/A' }}
                            </td>
                            <td class="text-secondary small" title="{{ $log->referer }}">
                                {{ $log->referer ? Str::limit($log->referer, 30) : 'Direct' }}
                            </td>
                            <td class="text-secondary font-monospace">{{ $log->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No extraction logs found in database.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($downloadLogs->hasPages())
            <div class="card-footer d-flex justify-content-center py-3">
                {{ $downloadLogs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- ── SECTION B: User Activity Summary ────────────────────────── -->
    <div class="card shadow-lg">
        <div class="card-header">
            <span class="fs-5">Top 20 User Activity Profiles (by Requests)</span>
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
                            <td><span class="font-monospace fw-semibold">{{ $activity->ip_address }}</span></td>
                            <td class="fw-bold">{{ $activity->total_requests }}</td>
                            <td>
                                <span class="text-success fw-bold">✓ {{ $activity->total_success }}</span>
                            </td>
                            <td>
                                <span class="text-danger fw-bold">✗ {{ $activity->total_failed }}</span>
                            </td>
                            <td class="text-secondary font-monospace">
                                {{ $activity->last_seen_at ? $activity->last_seen_at->format('Y-m-d H:i:s') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No user activity profile data accumulated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
