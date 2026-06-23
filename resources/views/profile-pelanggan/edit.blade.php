@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square text-primary me-2"></i>
            Profile
        </h2>
    </div>

    {{-- PROFILE INFORMATION --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-person-circle text-primary me-2"></i>
                Informasi Profile
            </h5>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="row g-3">

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nama Lengkap
                        </label>

                        <input id="name" type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}"
                            required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- NO HP --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            No HP
                        </label>

                        <input id="no_hp" type="text" name="no_hp"
                            class="form-control @error('no_hp') is-invalid @enderror"
                            value="{{ old('no_hp', $user->no_hp) }}" required>

                        @error('no_hp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- No Id Pelanggan --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            No Id Pelanggan
                        </label>

                        <input id="no_id_pelanggan" type="text" name="no_id_pelanggan"
                            class="form-control @error('no_id_pelanggan') is-invalid @enderror"
                            value="{{ old('no_id_pelanggan', $pelanggan->no_id_pelanggan) }}">

                        @error('no_id_pelanggan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- JENIS PELANGGAN --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Jenis Pelanggan
                        </label>

                        <select name="jenis_pelanggan" class="form-select @error('jenis_pelanggan') is-invalid @enderror"
                            required>

                            <option value="R1"
                                {{ old('jenis_pelanggan', $pelanggan->jenis_pelanggan) == 'R1' ? 'selected' : '' }}>
                                R1
                            </option>

                            <option value="R2"
                                {{ old('jenis_pelanggan', $pelanggan->jenis_pelanggan) == 'R2' ? 'selected' : '' }}>
                                R2
                            </option>

                            <option value="PK1"
                                {{ old('jenis_pelanggan', $pelanggan->jenis_pelanggan) == 'PK1' ? 'selected' : '' }}>
                                PK1
                            </option>

                            <option value="PK2"
                                {{ old('jenis_pelanggan', $pelanggan->jenis_pelanggan) == 'PK2' ? 'selected' : '' }}>
                                PK2
                            </option>

                        </select>

                        @error('jenis_pelanggan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- CABANG --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Cabang Terdaftar
                        </label>

                        <select id="cabang_id" name="cabang_id"
                            class="form-select @error('cabang_id') is-invalid @enderror">

                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->cabang_id }}"
                                    {{ old('cabang_id', $user->cabang_id) == $cabang->cabang_id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach

                        </select>

                        @error('cabang_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ALAMAT --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Alamat Lengkap
                        </label>

                        <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $pelanggan->alamat) }}</textarea>

                        @error('alamat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- LATITUDE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Latitude
                        </label>

                        <input id="latitude" type="number" step="any" name="latitude"
                            class="form-control @error('latitude') is-invalid @enderror"
                            value="{{ old('latitude', $pelanggan->latitude) }}" required>

                        @error('latitude')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- LONGITUDE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Longitude
                        </label>

                        <input id="longitude" type="number" step="any" name="longitude"
                            class="form-control @error('longitude') is-invalid @enderror"
                            value="{{ old('longitude', $pelanggan->longitude) }}" required>

                        @error('longitude')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="map-info">
                    <svg class="loading-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <path d="M12 5v2"></path>
                        <path d="M19.07 4.93l-1.414 1.414"></path>
                        <path d="M21 12h-2"></path>
                    </svg>
                    <span id="locationStatus">
                        📍 Tekan tombol di bawah untuk mendapatkan lokasi GPS Anda atau klik di peta untuk memilih lokasi
                    </span>
                </div>

                <button type="button" class="btn btn-info btn-get-location mb-3" id="getLocationBtn">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"
                        style="margin-right:8px;vertical-align:middle;">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Dapatkan Lokasi GPS
                </button>

                <div id="map"></div>

                <hr class="my-4">

                <div class="d-flex justify-content-end align-items-center gap-2">

                    @if (session('status') === 'profile-updated')
                        <span class="text-success small">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Profil berhasil diperbarui
                        </span>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- UPDATE PASSWORD --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-shield-lock text-warning me-2"></i>
                Update Password
            </h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">

                @csrf
                @method('put')

                <div class="row g-3">

                    {{-- PASSWORD LAMA --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            Password Saat Ini
                        </label>

                        <input id="current_password" type="password" name="current_password"
                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required
                            autocomplete="current-password">

                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- PASSWORD BARU --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Password Baru
                        </label>

                        <input id="password" type="password" name="password"
                            class="form-control @error('password', 'updatePassword') is-invalid @enderror" required
                            autocomplete="new-password">

                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Konfirmasi Password Baru
                        </label>

                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                            required>

                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end align-items-center gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Password
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        let map;
        let marker;
        const getLocationBtn = document.getElementById('getLocationBtn');
        const locationStatus = document.getElementById('locationStatus');
        const latitudeInput = document.querySelector('input[name="latitude"]');
        const longitudeInput = document.querySelector('input[name="longitude"]');
        const loadingSpinner = document.querySelector('.loading-spinner');

        function initializeMap() {
            const defaultLat = -2.9761;
            const defaultLng = 104.7754;

            map = L.map('map').setView([defaultLat, defaultLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                updateLocation(lat, lng);
            });

            const savedLat = latitudeInput.value;
            const savedLng = longitudeInput.value;
            if (savedLat && savedLng) {
                updateLocation(parseFloat(savedLat), parseFloat(savedLng));
                map.setView([parseFloat(savedLat), parseFloat(savedLng)], 15);
            }
        }

        function updateLocation(lat, lng) {
            const roundedLat = Math.round(lat * 100000000) / 100000000;
            const roundedLng = Math.round(lng * 100000000) / 100000000;

            latitudeInput.value = roundedLat;
            longitudeInput.value = roundedLng;

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([roundedLat, roundedLng]).addTo(map);
            marker.bindPopup(`Lokasi: ${roundedLat}<br>Longitude: ${roundedLng}`).openPopup();
            map.setView([roundedLat, roundedLng], 15);
            locationStatus.innerHTML = `✅ Lokasi dipilih!`;
        }

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

        getLocationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            getUserLocation();
        });

        document.addEventListener('DOMContentLoaded', initializeMap);
    </script>
@endsection
