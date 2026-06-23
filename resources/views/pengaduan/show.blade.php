@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-file-earmark-text text-primary me-2"></i>
            {{ $pengaduan->nomor_pengaduan }}
        </h2>

        <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-secondary flex-shrink-0">
            <i class="bi bi-arrow-left"></i> Kembali</span>
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-text text-danger me-2"></i>
                Informasi Pengaduan
            </h5>
        </div>

        <div class="card-body">
            <div class="row">

                <!-- Informasi Pengaduan -->
                <div class="col-md-9">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="text-muted small">Nomor Pengaduan:</label>
                            <div class="fw-semibold">{{ $pengaduan->nomor_pengaduan }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Pelanggan:</label>
                            <div>{{ $pengaduan->user->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Jenis Keluhan:</label>
                            <div>{{ $pengaduan->jenis_keluhan ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Stand Meter:</label>
                            <div>{{ $pengaduan->stand_meter_terakhir ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Pengaduan:</label>
                            <div>{{ $pengaduan->tanggal_pengaduan->format('d/m/Y H:i') }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Selesai:</label>
                            <div>{{ $pengaduan->tanggal_selesai?->format('d/m/Y H:i') ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">No HP:</label>
                            <div>{{ $pengaduan->user->no_hp ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Status Pengaduan:</label>
                            <div class="mt-1">
                                <span
                                    class="badge bg-{{ $pengaduan->status_pengaduan == 'selesai'
                                        ? 'success'
                                        : ($pengaduan->status_pengaduan == 'teknisi_ditugaskan'
                                            ? 'primary'
                                            : ($pengaduan->status_pengaduan == 'valid'
                                                ? 'info'
                                                : ($pengaduan->status_pengaduan == 'tidak_valid'
                                                    ? 'danger'
                                                    : 'warning'))) }}">
                                    {{ ucwords(str_replace('_', ' ', $pengaduan->status_pengaduan)) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small">Deskripsi Keluhan:</label>
                            <div>{{ $pengaduan->deskripsi_keluhan }}</div>
                        </div>

                        @if ($pengaduan->status_pengaduan == 'tidak_valid')
                            <div class="col-12">
                                <label class="text-muted small">Keterangan CS:</label>
                                <div class="border rounded p-3 bg-light">
                                    {{ $pengaduan->keterangan_cs ?? '-' }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Foto Keluhan -->
                <div class="col-md-3">
                    @if ($pengaduan->foto_keluhan)
                        <label class="text-muted small d-block mb-2">
                            Foto Keluhan:
                        </label>

                        <img src="{{ asset('storage/pengaduan/' . $pengaduan->foto_keluhan) }}"
                            class="img-fluid rounded shadow-sm border w-100"
                            style="max-height:300px; object-fit:cover; cursor:pointer;" onclick="openModal(this.src)">
                    @endif
                </div>
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

    <!-- Verifikasi CS -->
    @can('pengaduan.validate')
        @if ($pengaduan->status_pengaduan == 'pengajuan')
            <div class="card mb-3 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="bi bi-patch-check mb-0"> Verifikasi Pengaduan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengaduan.validate', $pengaduan->pengaduan_id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status Verifikasi</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pengaduan" id="valid"
                                        value="valid" required>
                                    <label class="form-check-label text-success fw-bold" for="valid">
                                        ✓ Valid
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_pengaduan" id="tidak_valid"
                                        value="tidak_valid" required>
                                    <label class="form-check-label text-danger fw-bold" for="tidak_valid">
                                        ✗ Tidak Valid
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" id="keterangan-container" style="display: none;">
                            <label for="keterangan_cs" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="keterangan_cs" id="keterangan_cs" rows="3"
                                placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                        <button type="submit" class="bi bi-save btn btn-primary"> Simpan Verifikasi</button>
                    </form>
                </div>
            </div>
        @endif
    @endcan

    <!-- Penugasan Teknisi -->
    @can('pengaduan.assign-teknisi')
        @if (trim($pengaduan->status_pengaduan) === 'valid')
            <div class="card mb-3 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="bi bi-card-checklist mb-0"> Penugasan Teknisi</h5>
                </div>
                <div class="card-body">
                    @if ($pengaduan->penilaianSaw)
                        <form action="{{ route('pengaduan.assign-teknisi', $pengaduan->pengaduan_id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <select class="form-select" name="user_id" required>
                                    <option value="">
                                        {{ $teknisi->isEmpty() ? '-- Tidak ada teknisi tersedia --' : '-- Pilih Teknisi --' }}
                                    </option>
                                    @foreach ($teknisi as $teknisiitem)
                                        <option value="{{ $teknisiitem->user_id }}">{{ $teknisiitem->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bi bi-person-gear btn btn-primary" @disabled($teknisi->isEmpty())>
                                Tugaskan Teknisi</button>
                        </form>
                    @else
                        <div class="alert alert-warning mb-0">
                            Penugasan teknisi belum bisa dilakukan karena penilaian SAW untuk pengaduan ini belum tersedia.
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endcan


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const validRadio = document.getElementById('valid');
            const tidakValidRadio = document.getElementById('tidak_valid');
            const keteranganContainer = document.getElementById('keterangan-container');
            const keteranganTextarea = document.getElementById('keterangan_cs');

            function toggleKeterangan() {
                if (tidakValidRadio.checked) {
                    keteranganContainer.style.display = 'block';
                    keteranganTextarea.setAttribute('required', 'required');
                } else {
                    keteranganContainer.style.display = 'none';
                    keteranganTextarea.removeAttribute('required');
                    keteranganTextarea.value = '';
                }
            }

            validRadio.addEventListener('change', toggleKeterangan);
            tidakValidRadio.addEventListener('change', toggleKeterangan);
        });
    </script>


    </div>
    </div>
@endsection
