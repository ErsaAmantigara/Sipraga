<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Web Pengaduan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 4px;
            margin-bottom: 2px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #34495e;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table-action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        #map {
            height: 300px;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
        }

        .map-info {
            background-color: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #0369a1;
        }

        .btn-get-location {
            width: 100%;
            margin-bottom: 1rem;
        }

        .loading-spinner {
            display: none;
        }

        .loading-spinner.show {
            display: inline-block;
        }

        .sidebar {
            background: #1a2744;
            min-height: calc(100vh - 56px);
        }

        .user-info .name {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .user-info .role {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        @media (min-width: 768px) {
            .offcanvas.offcanvas-start#sidebarOffcanvas {
                transform: none !important;
                visibility: visible !important;
                position: fixed;
                width: 250px;
                border: none;
                z-index: 1020;
            }

            .offcanvas-backdrop.show {
                display: none !important;
            }

            .content-area {
                margin-left: 250px;
                min-height: 100vh;
            }
        }

        @media (max-width: 767.98px) {
            .offcanvas.offcanvas-start#sidebarOffcanvas {
                width: 280px;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    @auth
        {{-- Sidebar Offcanvas --}}
        <div class="offcanvas offcanvas-start sidebar" tabindex="-1" id="sidebarOffcanvas">
            <div class="offcanvas-header">
                <div class="text-center w-100">
                    <div class="text-center mb-0">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow"
                            style="width: 80px; height: 80px;">

                            <img src="{{ asset('images/logo.jpg') }}" alt="Logo"
                                style="width: 70px; height: 70px; object-fit: contain;">
                        </div>
                    </div>
                    <span class="offcanvas-title text-white fw-bold">SIPRAGA</span>
                </div>
                <button type="button" class="btn-close btn-close-white d-md-none position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0">
                @include('layouts.sidebar')
            </div>
        </div>

        {{-- Content --}}
        <div class="content-area">
            @include('layouts.navbar')
            <div class="container-fluid py-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    @endauth

    @guest
        <div class="container-fluid p-0">
            @yield('content')
        </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Yakin ingin menghapus data ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Yakin ingin menghapus data ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
