<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - TW Downloader</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --sidebar-bg: #111827;
            --card-bg: #111827;
            --text-color: #f3f4f6;
            --border-color: #1f2937;
            --active-color: #3b82f6;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ── Sidebar Styling ────────────────────────────────────────── */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 24px;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            margin-bottom: 32px;
            padding-left: 8px;
        }

        .sidebar-brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: #2563eb;
            color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            color: #9ca3af;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: #1f2937;
        }

        .sidebar-link.active {
            color: #ffffff;
            background-color: #2563eb;
        }

        .sidebar-link-inner {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }

        /* ── Main Layout Wrapper ────────────────────────────────────── */
        .main-wrapper {
            margin-left: 280px;
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 72px;
            background-color: rgba(17, 24, 39, 0.7);
            border-bottom: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .admin-badge {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .admin-badge::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
        }

        .content-container {
            flex-grow: 1;
            padding: 40px;
        }

        /* Common component aesthetics */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-color);
            padding: 18px 24px;
            font-weight: 600;
            color: #ffffff;
        }

        .table {
            color: #e5e7eb;
            margin-bottom: 0;
        }

        .table th {
            color: #9ca3af;
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 20px;
        }

        .table td {
            border-bottom: 1px solid var(--border-color);
            padding: 14px 20px;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.01);
        }

        /* Pagination overrides */
        .pagination {
            margin: 0;
        }

        .page-link {
            background-color: #111827;
            border-color: #1f2937;
            color: #9ca3af;
            padding: 8px 16px;
        }

        .page-link:hover {
            background-color: #1f2937;
            border-color: #3b82f6;
            color: #ffffff;
        }

        .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        .page-item.disabled .page-link {
            background-color: #0b0f19;
            border-color: #1f2937;
            color: #4b5563;
        }
    </style>
</head>
<body>

    @php
        // Fetch unread count inline to ensure layout stays fully self-contained across all controller calls
        $unreadMessagesCountGlobal = \App\Models\ContactMessage::where('is_read', false)->count();
    @endphp

    <!-- ── Sidebar ──────────────────────────────────────────────────── -->
    <aside class="sidebar" role="navigation">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.741l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                </svg>
            </div>
            <span>TW Downloader</span>
        </a>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="sidebar-link-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.contacts') }}" class="sidebar-link {{ request()->routeIs('admin.contacts') ? 'active' : '' }}">
                    <div class="sidebar-link-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.03 6.862L9.75 8.161 8 9.21 6.25 8.161.03 12.145A1 1 0 0 0 1 13h14a1 1 0 0 0 .97-.855zM1 11.114l4.758-2.876L1 5.483v5.631z"/>
                        </svg>
                        <span>Contact Messages</span>
                    </div>
                    @if($unreadMessagesCountGlobal > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadMessagesCountGlobal }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start">
                    <div class="sidebar-link-inner text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        <span>Admin Logout</span>
                    </div>
                </button>
            </form>
        </div>
    </aside>

    <!-- ── Main Layout Wrapper ────────────────────────────────────── -->
    <div class="main-wrapper">
        <header class="top-navbar">
            <h2 class="page-title">@yield('page_header', 'System Hub')</h2>
            <div class="admin-badge">Admin Session Active</div>
        </header>

        <main class="content-container">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
