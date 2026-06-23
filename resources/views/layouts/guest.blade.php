<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SAW Complaint System') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #1e293b;
            --primary-light: #334155;
            --secondary: #3b82f6;
            --success: #10b981;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
        }
        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .brand-text {
            background: linear-gradient(135deg, #1e293b 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">
        <div class="auth-card p-4" style="width: 100%; max-width: 420px;">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white mb-3" style="width: 60px; height: 60px;">
                    <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <h4 class="brand-text fw-bold mb-1">SAW Complaint</h4>
                <p class="text-muted small">PT. Sarana Pembangunan Palembang Jaya</p>
            </div>
            @if (session('success'))
                <div class="alert alert-success py-2">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger py-2">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>