@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-building-add text-primary me-2"></i>
            Form Tambah Cabang
        </h2>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-plus-circle text-success me-2"></i>
                Tambah Cabang Baru
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('cabang.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Nama Cabang --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Cabang</label>
                        <input type="text" name="nama_cabang" value="{{ old('nama_cabang') }}" class="form-control"
                            required>
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" rows="4" class="form-control" required>{{ old('alamat') }}</textarea>
                    </div>

                    {{-- MAP INFO --}}
                    <div class="col-12">
                        <div class="map-info">
                            <svg class="loading-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="1"></circle>
                                <path d="M12 5v2"></path>
                                <path d="M19.07 4.93l-1.414 1.414"></path>
                                <path d="M21 12h-2"></path>
                            </svg>

                            <span id="locationStatus">
                                📍 Tekan tombol di bawah untuk mendapatkan lokasi GPS Anda atau klik di peta untuk memilih
                                lokasi
                            </span>
                        </div>
                    </div>

                    {{-- BUTTON GPS --}}
                    <div class="col-12">
                        <button type="button" class="btn btn-info w-100" id="getLocationBtn">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"
                                style="margin-right: 8px; vertical-align: middle;">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dapatkan Lokasi GPS
                        </button>
                    </div>

                    {{-- MAP --}}
                    <div class="col-12">
                        <div id="map"></div>
                    </div>

                    {{-- LAT LONG --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                            class="form-control">
                    </div>

                    {{-- BUTTON SUBMIT --}}
                    <hr>

                    <div class="d-flex flex-wrap justify-content-end gap-2">

                        <a href="{{ route('cabang.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Simpan Cabang
                        </button>

                    </div>
                </div>

        </div>
        </form>

    </div>
    </div>

    {{-- LEAFLET --}}
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
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            map.on('click', function(e) {
                updateLocation(e.latlng.lat, e.latlng.lng);
            });
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
                locationStatus.innerHTML = '❌ Geolocation tidak didukung browser';
                return;
            }

            loadingSpinner.classList.add('show');
            getLocationBtn.disabled = true;

            locationStatus.innerHTML = '⏳ Mengambil lokasi GPS...';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    updateLocation(position.coords.latitude, position.coords.longitude);

                    locationStatus.innerHTML = '✅ Lokasi GPS diperoleh!';

                    loadingSpinner.classList.remove('show');
                    getLocationBtn.disabled = false;
                },
                function(error) {
                    locationStatus.innerHTML = '❌ Gagal mengambil lokasi';

                    loadingSpinner.classList.remove('show');
                    getLocationBtn.disabled = false;
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
