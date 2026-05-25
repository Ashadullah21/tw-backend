<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - TW Downloader</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #060913;
            --sidebar-bg: #0b0f19;
            --card-bg: #0f1629;
            --text-primary: #f0f4ff;
            --text-secondary: #8b9abf;
            --text-muted: #5a6a8a;
            --border-color: rgba(255, 255, 255, 0.08);
            --brand-primary: #1d9bf0;
            --brand-primary-hover: #1585d2;
            --brand-glow: rgba(29, 155, 240, 0.15);
            --transition-speed: 0.25s;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            background-image: 
                radial-gradient(ellipse 60% 40% at 50% -10%, rgba(29, 155, 240, 0.08) 0%, transparent 60%);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
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
            padding: 30px 24px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.2rem;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            margin-bottom: 35px;
            padding-left: 8px;
            letter-spacing: -0.5px;
        }

        .sidebar-brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--brand-primary), #1570cf);
            color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(29, 155, 240, 0.3);
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
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.92rem;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            border: 1px solid transparent;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.02);
        }

        .sidebar-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand-primary), #1570cf);
            box-shadow: 0 6px 18px rgba(29, 155, 240, 0.25);
            border-color: transparent;
        }

        .sidebar-link-inner {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid var(--border-color);
            padding-top: 24px;
        }

        /* ── Main Layout Wrapper ────────────────────────────────────── */
        .main-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
            min-width: 0;
        }

        .top-navbar {
            height: 76px;
            background-color: rgba(6, 9, 19, 0.7);
            border-bottom: 1px solid var(--border-color);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
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
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .admin-badge {
            background-color: rgba(16, 185, 129, 0.08);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.1);
        }

        .admin-badge::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 8px #10b981;
        }

        .content-container {
            flex-grow: 1;
            padding: 40px;
        }

        /* ── Common Card Styling ────────────────────────────────────── */
        .card {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 20px !important;
            margin-bottom: 24px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.01) !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 20px 24px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            letter-spacing: -0.3px;
        }

        .table {
            color: var(--text-secondary);
            margin-bottom: 0;
        }

        .table th {
            color: var(--text-muted);
            border-bottom: 2px solid var(--border-color) !important;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 16px 24px;
            background-color: transparent !important;
        }

        .table td {
            border-bottom: 1px solid var(--border-color) !important;
            padding: 16px 24px;
            font-size: 0.9rem;
            vertical-align: middle;
            background-color: transparent !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.007) !important;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02) !important;
            color: #ffffff;
        }

        /* ── Pagination Styling ─────────────────────────────────────── */
        .pagination {
            margin: 0;
            gap: 6px;
        }

        .page-link {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-color: var(--border-color) !important;
            color: var(--text-secondary) !important;
            padding: 10px 18px !important;
            border-radius: 10px !important;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all var(--transition-speed) ease;
        }

        .page-link:hover {
            background-color: rgba(255, 255, 255, 0.06) !important;
            border-color: var(--brand-primary) !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--brand-primary), #1570cf) !important;
            border-color: transparent !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(29, 155, 240, 0.25);
        }

        .page-item.disabled .page-link {
            background-color: transparent !important;
            border-color: var(--border-color) !important;
            color: var(--text-muted) !important;
            opacity: 0.4;
        }

        /* ── Hamburger / Mobile Navigation elements ────────────────── */
        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }

        .hamburger-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--brand-primary);
        }

        .sidebar-overlay {
            display: none;
        }

        /* ── Premium Forms & Filters Styling ── */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-radius: 10px !important;
            font-size: 0.9rem !important;
            height: 44px !important;
            transition: all var(--transition-speed) ease !important;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.06) !important;
            border-color: var(--brand-primary) !important;
            box-shadow: 0 0 10px rgba(29, 155, 240, 0.2) !important;
            color: #ffffff !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35) !important;
        }
        .form-control::-webkit-input-placeholder {
            color: rgba(255, 255, 255, 0.35) !important;
        }
        .form-control::-moz-placeholder {
            color: rgba(255, 255, 255, 0.35) !important;
        }

        .input-group {
            border-radius: 10px !important;
            overflow: hidden !important;
        }

        .input-group-text {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-right: none !important;
            color: var(--text-secondary) !important;
            border-top-left-radius: 10px !important;
            border-bottom-left-radius: 10px !important;
            height: 44px !important;
            padding-left: 16px !important;
            padding-right: 12px !important;
        }

        .input-group .form-control {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-left: none !important;
        }

        .form-select option {
            background-color: var(--card-bg) !important;
            color: #ffffff !important;
        }

        /* Standardized Action Buttons */
        .btn-outline-primary {
            border-color: rgba(29, 155, 240, 0.4) !important;
            color: var(--brand-primary) !important;
            border-radius: 10px !important;
            height: 44px !important;
            padding: 0 20px !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all var(--transition-speed) ease !important;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--brand-primary), #1570cf) !important;
            border-color: transparent !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(29, 155, 240, 0.25) !important;
            transform: translateY(-1px) !important;
        }

        .btn-ghost {
            color: var(--text-secondary) !important;
            border: 1px solid transparent !important;
            background-color: transparent !important;
            border-radius: 10px !important;
            height: 44px !important;
            padding: 0 20px !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all var(--transition-speed) ease !important;
        }

        .btn-ghost:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .btn-export {
            background: linear-gradient(135deg, #10b981, #047857) !important;
            color: white !important;
            border: none !important;
            border-radius: 10px !important;
            height: 44px !important;
            padding: 0 20px !important;
            font-weight: 700 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
            transition: all var(--transition-speed) ease !important;
        }

        .btn-export:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.35) !important;
            filter: brightness(1.1) !important;
        }

        /* ── Media Queries ─────────────────────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }
            
            .sidebar.show {
                transform: translateX(0);
                box-shadow: 15px 0 40px rgba(0, 0, 0, 0.75);
            }

            .main-wrapper {
                margin-left: 0 !important;
            }

            .top-navbar {
                padding: 0 20px;
            }

            .hamburger-btn {
                display: inline-flex;
            }

            .sidebar-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                z-index: 1040;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.show {
                opacity: 1;
                pointer-events: auto;
            }

            .content-container {
                padding: 24px 16px;
            }
            
            .top-navbar {
                height: 70px;
            }
        }
    </style>
</head>
<body>

    @php
        // Fetch unread count inline to ensure layout stays fully self-contained across all controller calls
        $unreadMessagesCountGlobal = \App\Models\ContactMessage::where('is_read', false)->count();
    @endphp

    <!-- Mobile Drawer Overlay Backdrop -->
    <div class="sidebar-overlay"></div>

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
                        <span class="badge bg-danger rounded-pill px-2.5 py-1">{{ $unreadMessagesCountGlobal }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.mp3_downloads') }}" class="sidebar-link {{ request()->routeIs('admin.mp3_downloads') ? 'active' : '' }}">
                    <div class="sidebar-link-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6 13c0 1.105-1.12 2-2.5 2S1 14.105 1 13s1.12-2 2.5-2 2.5.895 2.5 2zm9-2c0 1.105-1.12 2-2.5 2S10 12.105 10 11s1.12-2 2.5-2 2.5.895 2.5 2z"/>
                            <path fill-rule="evenodd" d="M14 11V2h1v9h-1zM6 3v10H5V3h1z"/>
                            <path d="M5 2.905a1 1 0 0 1 .9-.975l8-1.22A1 1 0 0 1 15 1.705V4L5 5.5V2.905z"/>
                        </svg>
                        <span>MP3 Downloads</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.failed_downloads') }}" class="sidebar-link {{ request()->routeIs('admin.failed_downloads') ? 'active' : '' }}">
                    <div class="sidebar-link-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="color: #ff4b4b;">
                            <path d="M8 15a7 7 0 1 1 0-14 7 7 0 0 1 0 14zm0 1a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                        </svg>
                        <span>Failed Attempts</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.faqs.index') }}" class="sidebar-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <div class="sidebar-link-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8a8 8 0 1 1 0-16 8 8 0 0 1 0 16zM5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247zm2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                        </svg>
                        <span>Manage FAQs</span>
                    </div>
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
            <div class="d-flex align-items-center gap-3">
                <button class="hamburger-btn" id="sidebar-toggle" aria-label="Toggle Navigation Sidebar" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </button>
                <h2 class="page-title">@yield('page_header', 'System Hub')</h2>
            </div>
            <div class="admin-badge">Admin Session Active</div>
        </header>

        <main class="content-container">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Responsive Menu Toggle Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (toggleBtn && sidebar && overlay) {
                const toggleSidebar = () => {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                };

                toggleBtn.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
</body>
</html>
