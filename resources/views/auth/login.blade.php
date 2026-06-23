<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login -Pengaduan Keluhan Gas ALam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg,
                    #020617 0%,
                    #071938 35%,
                    #0a2a63 70%,
                    #123c7a 100%);
            min-height: 100vh;
        }

        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow"
                        style="width: 100px; height: 100px;">

                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo"
                            style="width: 90px; height: 90px; object-fit: contain;">
                    </div>
                </div>
                <h4 class="brand-text fw-bold mb-1">SIPRAGA</h4>
                <p class="text-muted small">PT. Sarana Pembangunan Palembang Jaya</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success py-2">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label class="form-label small fw-semibold">User ID</label>
                        <span class="small form-label fw-semibold" style="font-size: 12px"><span class="text-danger">*</span> User ID Merupakan Nomor Handphone</span>
                    </div>
                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp"
                        value="{{ old('no_hp') }}" required autofocus placeholder="081234567890">


                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                        required placeholder="••••••••">
                </div>


                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">Sign In</button>
                    @if (Route::has('password.request'))
                        <a class="btn btn-link text-center text-decoration-none small"
                            href="{{ route('password.request') }}">Forgot your password?</a>
                    @endif
                </div>

                <hr class="my-4">

                <p class="text-center small text-muted mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Register</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>
