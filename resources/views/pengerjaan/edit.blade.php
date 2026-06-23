@extends('layouts.app')

@section('title', 'Pengerjaan')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-tools text-primary me-2"></i>
            Form Pengerjaan
        </h2>
    </div>

    <div class="row">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-tools text-warning me-2"></i>
                     Pengerjaan
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('pengerjaan.update', $pengerjaan->pengerjaan_id) }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- STATUS --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Status Saat Ini
                            </label>

                            <input type="text" class="form-control"
                                value="{{ $pengerjaan->status_pengerjaan ? ucwords(str_replace('_', ' ', $pengerjaan->status_pengerjaan)) : 'Belum Ada Status' }}"
                                disabled>

                        </div>

                        {{-- MATERIAL --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Material
                            </label>

                            <textarea name="material" rows="3" class="form-control @error('material') is-invalid @enderror">{{ $pengerjaan->material }}</textarea>

                            @error('material')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        {{-- FOTO SEBELUM --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Foto Sebelum
                            </label>

                            <input type="file" name="foto_sebelum" class="form-control @error('foto_sebelum') is-invalid @enderror" accept="image/*">

                            @error('foto_sebelum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($pengerjaan->foto_sebelum)
                                <small class="text-muted d-block mt-1">Foto saat ini tersedia.</small>
                            @endif

                        </div>

                        {{-- FOTO PROSES --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Foto Proses
                            </label>

                            <input type="file" name="foto_proses" class="form-control @error('foto_proses') is-invalid @enderror" accept="image/*">

                            @error('foto_proses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($pengerjaan->foto_proses)
                                <small class="text-muted d-block mt-1">Foto saat ini tersedia.</small>
                            @endif

                        </div>

                        {{-- FOTO SESUDAH --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Foto Sesudah
                            </label>

                            <input type="file" name="foto_sesudah" class="form-control @error('foto_sesudah') is-invalid @enderror" accept="image/*">

                            @error('foto_sesudah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($pengerjaan->foto_sesudah)
                                <small class="text-muted d-block mt-1">Foto saat ini tersedia.</small>
                            @endif

                        </div>

                        {{-- KETERANGAN --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Keterangan Teknisi
                            </label>

                            <textarea name="keterangan_teknisi" rows="2" class="form-control">{{ $pengerjaan->keterangan_teknisi }}</textarea>

                        </div>

                    </div>

                    <hr>

                    <div class="d-flex flex-wrap gap-2 justify-content-end">

                        <a href="{{ route('pengerjaan.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Simpan Pengerjaan
                        </button>

                    </div>
                </form>

            </div>

        </div>

    @endsection
