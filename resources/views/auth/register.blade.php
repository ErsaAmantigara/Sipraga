<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - SAW Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg,
                    #0f1c58 0%,
                    #18315b 35%,
                    #00112f 70%,
                    #143a72 100%);
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
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">
        <div class="auth-card p-4" style="width: 100%; max-width: 760px;">
            <div class="text-center mb-4">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow"
                        style="width: 100px; height: 100px;">

                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo"
                            style="width: 90px; height: 90px; object-fit: contain;">
                    </div>
                </div>
                <h4 class="brand-text fw-bold mb-1">Register Pelanggan</h4>
                <p class="text-muted small">PT. Sarana Pembangunan Palembang Jaya</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" required autofocus placeholder="Nama pelanggan">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Nomor HP</label>
                        <input type="number" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp"
                            value="{{ old('no_hp') }}" required placeholder="081234567890">
                        @error('no_hp')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Jenis Pelanggan</label>
                        <select class="form-select @error('jenis_pelanggan') is-invalid @enderror"
                            name="jenis_pelanggan" required>
                            <option value="">-- Pilih jenis pelanggan --</option>
                            <option value="R1" @selected(old('jenis_pelanggan') === 'R1')>R1</option>
                            <option value="R2" @selected(old('jenis_pelanggan') === 'R2')>R2</option>
                            <option value="PK1" @selected(old('jenis_pelanggan') === 'PK1')>PK1</option>
                            <option value="PK2" @selected(old('jenis_pelanggan') === 'PK2')>PK2</option>
                        </select>
                        @error('jenis_pelanggan')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Cabang Terdaftar</label>
                        <select class="form-select @error('cabang_id') is-invalid @enderror" name="cabang_id" required>
                            <option value="">-- Pilih cabang --</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->cabang_id }}" @selected((string) old('cabang_id') === (string) $cabang->cabang_id)>
                                    {{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                        @error('cabang_id')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nomor ID Pelanggan</label>
                    <input type="text" class="form-control @error('no_id_pelanggan') is-invalid @enderror"
                        name="no_id_pelanggan" value="{{ old('no_id_pelanggan') }}" required
                        placeholder="ID pelanggan">
                    @error('no_id_pelanggan')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Alamat</label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3" required
                        placeholder="Alamat lengkap pelanggan">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="map-info">
                    <svg class="loading-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <path d="M12 5v2"></path>
                        <path d="M19.07 4.93l-1.414 1.414"></path>
                        <path d="M21 12h-2"></path>
                    </svg>
                    <span id="locationStatus">📍 Tekan tombol di bawah untuk mendapatkan lokasi GPS Anda atau klik di
                        peta untuk memilih lokasi</span>
                </div>

                <button type="button" class="btn btn-info btn-get-location mb-3" id="getLocationBtn">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"
                        style="margin-right: 8px; vertical-align: middle;">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Dapatkan Lokasi GPS
                </button>

                <div id="map"></div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Latitude</label>
                        <input type="number" step="any"
                            class="form-control @error('latitude') is-invalid @enderror" name="latitude"
                            value="{{ old('latitude') }}" required placeholder="-2.97607300">
                        @error('latitude')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Longitude</label>
                        <input type="number" step="any"
                            class="form-control @error('longitude') is-invalid @enderror" name="longitude"
                            value="{{ old('longitude') }}" required placeholder="104.77543000">
                        @error('longitude')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required
                            placeholder="Konfirmasi password">
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold">Daftarkan Akun Pelanggan</button>
                </div>

                <hr class="my-4">

                <p class="text-center small text-muted mb-0">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk</a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        let map;
        let marker;
        const getLocationBtn = document.getElementById('getLocationBtn');
        const locationStatus = document.getElementById('locationStatus');
        const latitudeInput = document.querySelector('input[name="latitude"]');
        const longitudeInput = document.querySelector('input[name="longitude"]');
        const loadingSpinner = document.querySelector('.loading-spinner');

        // Initialize map with Palembang default location
        function initializeMap() {
            const defaultLat = -2.9761;
            const defaultLng = 104.7754;

            map = L.map('map').setView([defaultLat, defaultLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Add click handler to map
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                updateLocation(lat, lng);
            });

            // Check if there's already a location saved
            const savedLat = latitudeInput.value;
            const savedLng = longitudeInput.value;
            if (savedLat && savedLng) {
                updateLocation(parseFloat(savedLat), parseFloat(savedLng));
                map.setView([parseFloat(savedLat), parseFloat(savedLng)], 15);
            }
        }

        // Update location on map and in form
        function updateLocation(lat, lng) {
            // Round to 8 decimal places
            const roundedLat = Math.round(lat * 100000000) / 100000000;
            const roundedLng = Math.round(lng * 100000000) / 100000000;

            // Update form inputs
            latitudeInput.value = roundedLat;
            longitudeInput.value = roundedLng;

            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }

            // Add new marker
            marker = L.marker([roundedLat, roundedLng]).addTo(map);
            marker.bindPopup(`Lokasi: ${roundedLat}<br>Longitude: ${roundedLng}`).openPopup();

            // Center map on marker
            map.setView([roundedLat, roundedLng], 15);

            // Update status
            locationStatus.innerHTML = `✅ Lokasi dipilih!`;
        }

        // Get user's GPS location
        function getUserLocation() {
            if (!navigator.geolocation) {
                locationStatus.innerHTML = '❌ Geolocation tidak didukung browser Anda';
                return;
            }

            loadingSpinner.classList.add('show');
            getLocationBtn.disabled = true;
            locationStatus.innerHTML =
                '<span class="loading-spinner show" style="display:inline-block;margin-right:8px;"></span>Mengambil lokasi GPS...';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    updateLocation(lat, lng);
                    locationStatus.innerHTML = `✅ Lokasi GPS diperoleh!`;

                    loadingSpinner.classList.remove('show');
                    getLocationBtn.disabled = false;
                },
                function(error) {
                    let errorMsg = '';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = '❌ Izin akses lokasi ditolak. Mohon aktifkan izin GPS di browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = '❌ Informasi lokasi tidak tersedia. Pastikan GPS aktif.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = '❌ Waktu tunggu habis. Silakan coba lagi.';
                            break;
                        default:
                            errorMsg = '❌ Terjadi kesalahan saat mengambil lokasi.';
                    }
                    locationStatus.innerHTML = errorMsg;
                    loadingSpinner.classList.remove('show');
                    getLocationBtn.disabled = false;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        // Event listener for get location button
        getLocationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            getUserLocation();
        });

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', initializeMap);
    </script>
