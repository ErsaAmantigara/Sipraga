@extends('layouts.app')

@section('title', 'Detail Pengerjaan')

@section('content')

@section('styles')
    <style>
        .star-rating {
            direction: rtl;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #d3d3d3;
            cursor: pointer;
            font-size: 2rem;
            transition: all .2s ease;
            margin: 0;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            transform: scale(1.1);
        }

        .star-rating input:checked~label {
            color: #ffc107;
        }

        .rating-hint {
            font-size: .8rem;
            color: #6c757d;
            text-align: center;
            line-height: 1.5;
        }
    </style>
@endsection

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="fw-bold mb-0">
        <i class="bi bi-tools text-primary me-2"></i>
        Pengerjaan #{{ $pengerjaan->pengerjaan_id }}
    </h2>

    <a href="{{ route('pengerjaan.index') }}" class="btn btn-sm btn-secondary flex-shrink-0">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

</div>

@php
    $profilepelanggan = $pengerjaan->pengaduan->user->profilepelanggan;

    $mapsUrl =
        $profilepelanggan?->latitude && $profilepelanggan?->longitude
            ? 'https://www.google.com/maps?q=' . $profilepelanggan->latitude . ',' . $profilepelanggan->longitude
            : null;
@endphp

{{-- INFORMASI PENGERJAAN --}}
<div class="card shadow-sm border-0 mb-3">

    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-tools text-primary me-2"></i>
            Informasi Pengerjaan
        </h5>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="text-muted small">Teknisi</label>
                <div>{{ $pengerjaan->teknisi->name ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted small">Nomor Pengaduan</label>
                <div>{{ $pengerjaan->pengaduan->nomor_pengaduan }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted small">Tanggal Mulai</label>
                <div>
                    {{ $pengerjaan->tanggal_mulai?->format('d/m/Y H:i') ?? '-' }}
                </div>
            </div>


            <div class="col-md-6">
                <label class="text-muted small">Tanggal Selesai</label>
                <div>
                    {{ $pengerjaan->tanggal_selesai?->format('d/m/Y H:i') ?? '-' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted small">Status Pengerjaan</label>
                <div class="mt-1">

                    @if ($pengerjaan->status_pengerjaan)
                        <span
                            class="badge bg-{{ $pengerjaan->status_pengerjaan == 'selesai'
                                ? 'success'
                                : ($pengerjaan->status_pengerjaan == 'dalam_pengerjaan'
                                    ? 'primary'
                                    : 'warning') }}">
                            {{ ucwords(str_replace('_', ' ', $pengerjaan->status_pengerjaan)) }}
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            Belum Ada Status
                        </span>
                    @endif

                </div>
            </div>





            <div class="col-md-6">
                <label class="text-muted small">Material</label>
                <div>{{ $pengerjaan->material ?? '-' }}</div>
            </div>

            <div class="col-12">
                <label class="text-muted small">
                    Keterangan Teknisi
                </label>

                <div class="border rounded p-3 bg-light">
                    {{ $pengerjaan->keterangan_teknisi ?? '-' }}
                </div>
            </div>

        </div>

    </div>

</div>

{{-- LOKASI PELANGGAN --}}
<div class="card shadow-sm border-0 mb-3">

    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
            Informasi Lokasi Pelanggan
        </h5>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-12">
                <label class="text-muted small">Alamat</label>
                <div>{{ $profilepelanggan?->alamat ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted small">Latitude</label>
                <div>{{ $profilepelanggan?->latitude ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted small">Longitude</label>
                <div>{{ $profilepelanggan?->longitude ?? '-' }}</div>
            </div>

            @if ($mapsUrl)
                <div class="col-12">
                    <a href="{{ $mapsUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-map me-1"></i>
                        Buka di Google Maps
                    </a>
                </div>
            @endif

        </div>

    </div>

</div>

{{-- FOTO DOKUMENTASI --}}
<div class="card shadow-sm border-0 mb-3">

    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-images text-success me-2"></i>
            Foto Dokumentasi
        </h5>
    </div>

    <div class="card-body">

        <div class="row">

            @if ($pengerjaan->foto_sebelum)
                <div class="col-md-4 mb-3">
                    <label class="text-muted small d-block mb-2">
                        Sebelum
                    </label>

                    <img src="{{ asset('storage/pengerjaan/' . $pengerjaan->foto_sebelum) }}"
                        class="img-fluid rounded shadow-sm" height="150" width="150" style="cursor: pointer;"
                        onclick="openModal(this.src)">
                </div>
            @endif

            @if ($pengerjaan->foto_proses)
                <div class="col-md-4 mb-3">
                    <label class="text-muted small d-block mb-2">
                        Proses
                    </label>

                    <img src="{{ asset('storage/pengerjaan/' . $pengerjaan->foto_proses) }}"
                        class="img-fluid rounded shadow-sm" height="150" width="150" style="cursor: pointer;"
                        onclick="openModal(this.src)">
                </div>
            @endif

            @if ($pengerjaan->foto_sesudah)
                <div class="col-md-4 mb-3">
                    <label class="text-muted small d-block mb-2">
                        Sesudah
                    </label>

                    <img src="{{ asset('storage/pengerjaan/' . $pengerjaan->foto_sesudah) }}"
                        class="img-fluid rounded shadow-sm" height="150" width="150" style="cursor: pointer;"
                        onclick="openModal(this.src)">
                </div>
            @endif

            @if (!$pengerjaan->foto_sebelum && !$pengerjaan->foto_proses && !$pengerjaan->foto_sesudah)
                <div class="col-12">
                    <div class="alert alert-light mb-0">
                        Belum ada foto dokumentasi.
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>

<!-- Modal Foto -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalPhoto" src="" class="img-fluid" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(src) {
        document.getElementById('modalPhoto').src = src;
        var modal = new bootstrap.Modal(document.getElementById('photoModal'));
        modal.show();
    }
</script>

@if ($pengerjaan->rating_nilai)

    <div class="card shadow-sm border-0 mb-3">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-star-fill text-warning me-2"></i>
                Rating Pelanggan
            </h5>
        </div>

        <div class="card-body">

            <div class="mb-3">

                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $pengerjaan->rating_nilai ? '-fill' : '' }} text-warning"></i>
                @endfor

                <span class="fw-semibold ms-2">
                    ({{ $pengerjaan->rating_nilai }}/5)
                </span>

            </div>

            <label class="text-muted small mb-2">
                Komentar Pelanggan
            </label>

            <div class="border rounded p-3 bg-light">
                {{ $pengerjaan->rating_komentar ?? '-' }}
            </div>

        </div>

    </div>
@elseif(auth()->user()->hasRole('pelanggan') &&
        $pengerjaan->pengaduan->user_id === auth()->id() &&
        $pengerjaan->status_pengerjaan === 'selesai')
    <div class="card shadow-sm border-0 mb-3">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-star text-warning me-2"></i>
                Beri Rating Pengerjaan
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('pengerjaan.rating', $pengerjaan->pengerjaan_id) }}" method="POST">

                @csrf

                {{-- Rating --}}
                <div class="mb-4 text-center">

                    <label class="form-label fw-semibold d-block">
                        Rating Pengerjaan
                    </label>

                    <div class="star-rating">

                        @for ($r = 5; $r >= 1; $r--)
                            <input type="radio" id="star{{ $r }}" name="rating_nilai"
                                value="{{ $r }}" required>

                            <label for="star{{ $r }}">
                                <i class="bi bi-star-fill"></i>
                            </label>
                        @endfor

                    </div>

                    <div class="rating-hint mt-2">
                        ⭐ 1: Sangat Buruk |
                        ⭐ 2: Buruk |
                        ⭐ 3: Cukup |
                        ⭐ 4: Baik |
                        ⭐ 5: Sangat Baik
                    </div>

                    @error('rating_nilai')
                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Komentar --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Komentar
                    </label>

                    <textarea name="rating_komentar" rows="4" class="form-control @error('rating_komentar') is-invalid @enderror"
                        placeholder="Tuliskan pengalaman Anda terhadap pengerjaan teknisi...">{{ old('rating_komentar') }}</textarea>

                    @error('rating_komentar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="d-flex justify-content-end">

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-send me-1"></i>
                        Kirim Rating

                    </button>

                </div>

            </form>

        </div>

    </div>

@endif
@endsection
