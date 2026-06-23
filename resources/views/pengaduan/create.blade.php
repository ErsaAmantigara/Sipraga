@extends('layouts.app')

@section('title', 'Tambah Pengaduan')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-plus-circle text-primary me-2"></i>
                Form Pengaduan
            </h2>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-plus text-success me-2"></i>
                Pengaduan Baru
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                        Jenis Keluhan
                        </label>

                        <select name="jenis_keluhan" class="form-select @error('jenis_keluhan') is-invalid @enderror"
                            required>

                            <option value="">
                                -- Pilih Jenis Keluhan --
                            </option>

                            @foreach ($jenisKeluhanList as $jk)
                                <option value="{{ $jk }}"
                                    {{ old('jenis_keluhan') == $jk ? 'selected' : '' }}>
                                    {{ $jk }}
                                </option>
                            @endforeach

                        </select>

                        @error('jenis_keluhan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Deskripsi Keluhan
                        </label>

                        <textarea name="deskripsi_keluhan" rows="5" class="form-control @error('deskripsi_keluhan') is-invalid @enderror"
                            placeholder="Jelaskan keluhan yang Anda alami..." required>{{ old('deskripsi_keluhan') }}</textarea>

                        @error('deskripsi_keluhan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Stand Meter Terakhir
                        </label>

                        <input type="number" name="stand_meter_terakhir"
                            class="form-control @error('stand_meter_terakhir') is-invalid @enderror"
                            value="{{ old('stand_meter_terakhir') }}" required>

                        @error('stand_meter_terakhir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Foto Keluhan
                        </label>

                        <input type="file" name="foto_keluhan"
                            class="form-control @error('foto_keluhan') is-invalid @enderror" accept="image/*" required>

                        <div class="form-text">
                            Upload foto kondisi terbaru sebagai bukti keluhan.
                        </div>

                        @error('foto_keluhan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <hr>

                <div class="d-flex flex-wrap justify-content-end gap-2">

                    <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button type="submit" id="btn-submit-pengaduan" class="btn btn-sm btn-primary">
                        <i class="bi bi-send-check me-1"></i>
                        Submit Pengaduan
                    </button>

                    @section('scripts')
                    <script>
                    document.getElementById('btn-submit-pengaduan')?.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: 'Apakah Anda yakin ingin mengirim pengaduan ini? Pastikan seluruh informasi sudah benar.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#0d6efd',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Kirim!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                    </script>
                    @endsection

                </div>

            </form>

        </div>

    </div>

@endsection
