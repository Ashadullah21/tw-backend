<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TW Downloader</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #060913;
            --card-bg: rgba(15, 22, 41, 0.7);
            --text-color: #f0f4ff;
            --text-muted: #8b9abf;
            --border-color: rgba(255, 255, 255, 0.08);
            --primary-blue: #1d9bf0;
            --primary-glow: rgba(29, 155, 240, 0.25);
            --transition-speed: 0.25s;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
            background-image: 
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(29, 155, 240, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 110%, rgba(99, 43, 235, 0.08) 0%, transparent 60%);
        }

        /* Sleek animated mesh glows */
        .glow-sphere {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(29, 155, 240, 0.15) 0%, rgba(29, 155, 240, 0) 70%);
            top: -150px;
            right: -100px;
            z-index: 0;
            filter: blur(40px);
            animation: floatGlow 12s ease-in-out infinite alternate;
        }

        .glow-sphere-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.09) 0%, rgba(139, 92, 246, 0) 70%);
            bottom: -200px;
            left: -150px;
            z-index: 0;
            filter: blur(40px);
            animation: floatGlow 18s ease-in-out infinite alternate-reverse;
        }

        @keyframes floatGlow {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(30px) scale(1.1); }
        }

        .login-container {
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 40px rgba(29, 155, 240, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 45px 35px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            border-color: rgba(29, 155, 240, 0.2);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(29, 155, 240, 0.08);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--primary-blue), #1570cf);
            color: #ffffff;
            border-radius: 14px;
            margin-bottom: 16px;
            box-shadow: 0 6px 20px rgba(29, 155, 240, 0.35);
            transition: transform var(--transition-speed) ease;
        }

        .login-card:hover .brand-icon {
            transform: scale(1.05) rotate(5deg);
        }

        .brand-title {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
            background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-group {
            position: relative;
            margin-bottom: 24px;
        }

        .form-control {
            background-color: rgba(6, 9, 19, 0.5) !important;
            border: 1.5px solid var(--border-color) !important;
            color: #ffffff !important;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all var(--transition-speed) ease !important;
        }

        .form-control::placeholder {
            color: rgba(139, 154, 191, 0.45) !important;
        }

        .form-control:focus {
            background-color: rgba(6, 9, 19, 0.8) !important;
            border-color: var(--primary-blue) !important;
            box-shadow: 0 0 0 4px var(--primary-glow) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), #1570cf) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 14px !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            transition: all var(--transition-speed) ease !important;
            box-shadow: 0 4px 15px rgba(29, 155, 240, 0.3) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2fa9ff, #1d82eb) !important;
            box-shadow: 0 6px 20px rgba(29, 155, 240, 0.45) !important;
            transform: translateY(-1.5px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .error-banner {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 14px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
    </style>
</head>
<body>

    <div class="glow-sphere"></div>
    <div class="glow-sphere-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="brand-header">
                <div class="brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.741l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </div>
                <h1 class="brand-title">TW Downloader</h1>
                <p class="brand-subtitle">Admin Security Portal</p>
            </div>

            <!-- Validation Error Banner -->
            @if ($errors->any())
                <div class="error-banner" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.993c.16-.966 1.438-.966 1.598 0l.585 3.511c.075.462-.269.85-.734.85h-.854c-.465 0-.81-.388-.734-.85l.585-3.511z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="admin@example.com" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Security Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required 
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mt-2">Sign In to Dashboard</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
