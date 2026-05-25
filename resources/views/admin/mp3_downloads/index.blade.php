@extends('admin.layout')

@section('title', 'MP3 Downloads')
@section('page_header', 'MP3 Download Tracking')

@section('content')
<div class="container-fluid p-0 overflow-hidden">

    <!-- ── Stats Overview ─────────────────────────────────────────── -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 p-4" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.03)) !important; box-shadow: 0 4px 20px rgba(99, 102, 241, 0.05);">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="small text-uppercase fw-bold mb-0" style="letter-spacing: 0.08em; color: rgba(255, 255, 255, 0.55) !important;">Audio Logs</p>
                    <div class="p-2 rounded-3 bg-indigo bg-opacity-10 text-indigo" style="color: #818cf8 !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6 13c0 1.105-1.12 2-2.5 2S1 14.105 1 13s1.12-2 2.5-2 2.5.895 2.5 2zm9-2c0 1.105-1.12 2-2.5 2S10 12.105 10 11s1.12-2 2.5-2 2.5.895 2.5 2z"/>
                            <path fill-rule="evenodd" d="M14 11V2h1v9h-1zM6 3v10H5V3h1z"/>
                            <path d="M5 2.905a1 1 0 0 1 .9-.975l8-1.22A1 1 0 0 1 15 1.705V4L5 5.5V2.905z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="mb-1 fw-extrabold text-white" style="letter-spacing: -0.5px;">{{ $mp3Downloads->total() }} Total MP3 Downloads</h3>
                <p class="small text-secondary mb-0">List of successfully transcoded and downloaded MP3 audios from Twitter/X links</p>
            </div>
        </div>
    </div>

    <!-- ── SECTION: MP3 Log Table ────────────────────────────────── -->
    <div class="card mb-5">
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <span class="fs-5 fw-bold">MP3 Transcoding & Download Logs</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1.5" style="background-color: rgba(255, 255, 255, 0.08) !important; color: #a5b4fc; border: 1px solid rgba(255, 255, 255, 0.05);">{{ $mp3Downloads->total() }} total entries</span>
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
                        <input type="text" name="search" class="form-control text-white" placeholder="Search by title, URL, IP or referer..." value="{{ request('search') }}">
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
                    <a href="{{ route('admin.export.mp3_downloads', request()->query()) }}" class="btn btn-export px-3 fw-bold d-inline-flex align-items-center gap-1.5">
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
                        <th>Target Audio Title</th>
                        <th>Source URL</th>
                        <th>IP Address</th>
                        <th>User Agent (Device Info)</th>
                        <th>Referer</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mp3Downloads as $log)
                        <tr>
                            <td class="fw-semibold" style="color: rgba(255, 255, 255, 0.45) !important;">{{ $loop->iteration + ($mp3Downloads->currentPage() - 1) * $mp3Downloads->perPage() }}</td>
                            <td class="text-white fw-semibold">{{ $log->title ?: 'N/A' }}</td>
                            <td>
                                <a href="{{ $log->url }}" target="_blank" class="text-primary text-decoration-none fw-semibold" title="{{ $log->url }}" style="color: var(--brand-primary) !important;">
                                    {{ Str::limit($log->url, 45) }}
                                </a>
                            </td>
                            <td><span class="font-monospace text-light fw-medium">{{ $log->ip_address }}</span></td>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-music-note-beamed mb-2 text-muted opacity-50" viewBox="0 0 16 16">
                                    <path d="M6 13c0 1.105-1.12 2-2.5 2S1 14.105 1 13s1.12-2 2.5-2 2.5.895 2.5 2zm9-2c0 1.105-1.12 2-2.5 2S10 12.105 10 11s1.12-2 2.5-2 2.5.895 2.5 2z"/>
                                    <path fill-rule="evenodd" d="M14 11V2h1v9h-1zM6 3v10H5V3h1z"/>
                                </svg>
                                <div>No MP3 download logs found in database.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($mp3Downloads->isNotEmpty())
            <div class="card-footer d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-4 bg-transparent border-0">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- Entries per page selector -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-secondary">Show</span>
                        <select onchange="window.location.href = this.value;" class="form-select form-select-sm text-white" style="width: auto; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; font-weight: 600; padding: 4px 28px 4px 12px; cursor: pointer;">
                            @foreach([10, 20, 50, 100] as $size)
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ $mp3Downloads->perPage() == $size ? 'selected' : '' }} style="background-color: var(--card-bg); color: var(--text-primary);">
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        <span class="small text-secondary">entries</span>
                    </div>
                    
                    <!-- Total count stats -->
                    <span class="small text-secondary">
                        Showing <span class="fw-semibold text-white">{{ $mp3Downloads->firstItem() ?? 0 }}</span> to 
                        <span class="fw-semibold text-white">{{ $mp3Downloads->lastItem() ?? 0 }}</span> of 
                        <span class="fw-semibold text-white">{{ $mp3Downloads->total() }}</span> entries
                    </span>
                </div>

                <!-- Page navigations -->
                @if($mp3Downloads->hasPages())
                    <div class="d-flex justify-content-center m-0">
                        {{ $mp3Downloads->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection
