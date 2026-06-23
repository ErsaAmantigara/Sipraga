<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SAW Complaint System - PT. Sarana Pembangunan Palembang Jaya</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Material Symbols -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <style>
        :root {
            --primary: #60a5fa;
            --primary-dark: #2563eb;
            --text: #f8fafc;
            --muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text);
            position: relative;

            background:
                radial-gradient(circle at top left,
                    rgba(59, 130, 246, 0.22),
                    transparent 30%),

                radial-gradient(circle at bottom right,
                    rgba(14, 165, 233, 0.18),
                    transparent 30%),

                radial-gradient(circle at center,
                    rgba(99, 102, 241, 0.10),
                    transparent 35%),

                linear-gradient(135deg,
                    #020617 0%,
                    #071126 25%,
                    #0b1730 50%,
                    #102040 75%,
                    #0f172a 100%);
        }

        /* ANIMATED BACKGROUND */

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cg fill='white' fill-opacity='0.03'%3E%3Ccircle cx='20' cy='20' r='2'/%3E%3Ccircle cx='80' cy='80' r='1.5'/%3E%3Ccircle cx='130' cy='50' r='1.5'/%3E%3Ccircle cx='40' cy='120' r='1.5'/%3E%3C/g%3E%3C/svg%3E");
            animation: moveBg 30s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes moveBg {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-160px);
            }
        }

        /* GLOW EFFECT */

        .glow {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: .25;
            z-index: 0;
            animation: floatGlow 8s ease-in-out infinite;
        }

        .glow-1 {
            width: 300px;
            height: 300px;
            background: #2563eb;
            top: -80px;
            left: -80px;
        }

        .glow-2 {
            width: 260px;
            height: 260px;
            background: #0ea5e9;
            bottom: -60px;
            right: -60px;
            animation-delay: 2s;
        }

        @keyframes floatGlow {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(25px);
            }
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 40px;
            position: relative;
            z-index: 2;
        }

        .content-wrapper {
            width: 100%;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 70px;
            align-items: center;
        }

        @media (max-width: 992px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }

        /* LEFT */

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;

            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7dd3fc;
        }

        .eyebrow-line {
            width: 36px;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(to right, #60a5fa, transparent);
        }

        .text-content {
            animation: fadeUp 1s ease;
        }

        .text-content h1 {
            font-size: 2.6rem;
            line-height: 1.08;
            font-weight: 800;
            margin-bottom: 22px;
            letter-spacing: -0.03em;
        }

        .text-content h1 span {
            color: #7dd3fc;
        }

        .text-content p.description {
            color: var(--muted);
            font-size: 0.98rem;
            line-height: 1.9;
            margin-bottom: 30px;
            max-width: 620px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(35px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* FEATURES */

        .features {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            padding: 16px;
            border-radius: 18px;
            backdrop-filter: blur(14px);
        }

        .feature-item:hover {
            border-color: rgba(96, 165, 250, 0.45);
            background: rgba(255, 255, 255, 0.07);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(96, 165, 250, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon span {
            color: #7dd3fc;
            font-size: 25px;
        }

        .feature-text h3 {
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .feature-text p {
            color: var(--muted);
            font-size: 0.85rem;
            line-height: 1.6;
        }

        /* BUTTON */

        .btn-group {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 35px;
        }

        .btn {
            padding: 13px 24px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: .92rem;
            transition: .3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-primary:hover {
              background: #0048ff4d;
        }

        .btn-outline {
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: white;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.09);
        }

        /* RIGHT PANEL */

        .guide-panel {
            background: rgba(15, 23, 42, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            padding: 30px;
            backdrop-filter: blur(24px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            animation: fadeUp 1s ease;
        }

        .guide-header {
            margin-bottom: 24px;
        }

        .guide-title {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
        }

        .guide-title-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: rgba(59, 130, 246, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .guide-title-icon span {
            color: #93c5fd;
            font-size: 28px;
        }

        .guide-title h2 {
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .guide-header p {
            color: var(--muted);
            line-height: 1.7;
            font-size: .92rem;
            margin-top: 8px;
        }

        /* STEP CARD */

        .steps {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .step-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: rgba(255, 255, 255, 0.04);
            border-left: 4px solid #93c5fd;
            border-radius: 20px;
            padding: 18px;
            transition: .3s ease;
        }

        .step-card:hover {
            background: rgba(255, 255, 255, 0.09);
        }

        .step-number {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.18);
            color: #bfdbfe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-content h3 {
            font-size: 1.05rem;
            margin-bottom: 4px;
        }

        .step-content p {
            color: #cbd5e1;
            font-size: 0.84rem;
            line-height: 1.6;
        }

        .step-icon span {
            color: rgba(255, 255, 255, 0.35);
            font-size: 28px;
            transition: .3s ease;
        }

        .step-card:hover .step-icon span {
            color: #93c5fd;
        }

        /* FOOTER */

        .footer {
            width: 100%;
            padding: 25px 0px;
            display: flex;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .footer-content {
            width: 100%;
            max-width: 100%;
            text-align: center;
        }

        .footer-line {
            width: 100%;
            height: 1px;
            border: 1px solid rgb(255, 255, 255);
            margin-bottom: 20px;
        }

        .footer p {
            color: #64748b;
            font-size: 0.78rem;
            letter-spacing: 0.03em;
        }

        /* MOBILE */

        @media (max-width: 768px) {

            .hero-section {
                padding: 40px 22px;
            }

            .text-content {
                text-align: center;
            }

            .eyebrow {
                justify-content: center;
            }

            .text-content h1 {
                font-size: 1.5rem;
                line-height: 1.15;
            }

            .text-content p.description {
                margin-inline: auto;
                font-size: .92rem;
            }

            .btn-group {
                justify-content: center;
            }

            .guide-panel {
                padding: 24px;
            }

            .guide-title h2 {
                font-size: 1.3rem;
            }

            .footer p {
                font-size: 0.72rem;
                line-height: 1.6;
            }
        }
    </style>
</head>

<body>

    <!-- Glow -->
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>

    <section class="hero-section">
        <div class="content-wrapper">

            <!-- LEFT -->
            <div class="text-content">

                <div class="eyebrow">
                    <span class="eyebrow-line"></span>
                    LAYANAN PENGADUAN GAS ALAM
                </div>

                <h1>
                    SIPRAGA <span>PT. Sarana</span>
                    <br>
                    <span>Pembangunan Palembang</span>
                    <br>
                    <span>Jaya</span>
                </h1>

                <p class="description">
                    Laporkan gangguan atau kendala gas alam Anda dengan cepat,
                    mudah dan transparan. Tim teknis kami siap membantu proses
                    penanganan secara tepat dan cepat demi kenyamanan pelanggan.
                </p>

                <div class="btn-group">

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Sign In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline">
                                Register
                            </a>
                        @endif
                    @endauth

                </div>

                <div class="features">

                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="material-symbols-outlined">verified</span>
                        </div>

                        <div class="feature-text">
                            <h3>Pelayanan Prima</h3>
                            <p>
                                Berkomitmen menangani keluhan pelanggan dengan efisien.
                            </p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="material-symbols-outlined">monitoring</span>
                        </div>

                        <div class="feature-text">
                            <h3>Tracking Real-Time</h3>
                            <p>
                                Pantau status pengaduan Anda secara langsung kapan saja.
                            </p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="material-symbols-outlined">engineering</span>
                        </div>

                        <div class="feature-text">
                            <h3>Teknisi Profesional</h3>
                            <p>
                                Didukung oleh staf teknis berpengalaman untuk penanganan maksimal.
                            </p>
                        </div>
                    </div>

                </div>


            </div>

            <!-- RIGHT -->
            <div class="guide-panel">

                <div class="guide-header">

                    <div class="guide-title">

                        <div class="guide-title-icon">
                            <span class="material-symbols-outlined">
                                assignment
                            </span>
                        </div>

                        <h2>
                            Cara Melaporkan Pengaduan
                        </h2>

                    </div>

                    <p>
                        Ikuti langkah mudah berikut untuk menyampaikan keluhan Anda.
                    </p>

                </div>

                <div class="steps">

                    <div class="step-card">

                        <div class="step-number">1</div>

                        <div class="step-content">
                            <h3>Daftar</h3>
                            <p>
                                Registrasi akun untuk login ke dalam sistem.
                            </p>
                        </div>

                        <div class="step-icon">
                            <span class="material-symbols-outlined">
                                person_add
                            </span>
                        </div>

                    </div>

                    <div class="step-card">

                        <div class="step-number">2</div>

                        <div class="step-content">
                            <h3>Isi Form</h3>
                            <p>
                                Lengkapi formulir pengaduan dengan detail kendala.
                            </p>
                        </div>

                        <div class="step-icon">
                            <span class="material-symbols-outlined">
                                edit_note
                            </span>
                        </div>

                    </div>

                    <div class="step-card">

                        <div class="step-number">3</div>

                        <div class="step-content">
                            <h3>Kirim</h3>
                            <p>
                                Kirim laporan untuk diproses oleh tim teknis.
                            </p>
                        </div>

                        <div class="step-icon">
                            <span class="material-symbols-outlined">
                                send
                            </span>
                        </div>

                    </div>

                    <div class="step-card">

                        <div class="step-number">4</div>

                        <div class="step-content">
                            <h3>Tunggu Proses</h3>
                            <p>
                                Pantau progres penanganan secara real-time.
                            </p>
                        </div>

                        <div class="step-icon">
                            <span class="material-symbols-outlined">
                                schedule
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-line"></div>

            <p>
                © 2026 D-IV Manajemen Informatika - 8MIC
            </p>
        </div>
    </footer>

</body>

</html>
