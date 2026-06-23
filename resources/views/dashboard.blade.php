@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')

    @php
        $pengaduanBadgeMap = [
            'pengajuan' => 'warning',
            'valid' => 'info',
            'teknisi_ditugaskan' => 'primary',
            'selesai' => 'success',
            'tidak_valid' => 'danger',
        ];

        $pengerjaanBadgeMap = [
            null => 'secondary',
            'dalam_pengecekan' => 'warning',
            'dalam_pengerjaan' => 'primary',
            'selesai' => 'success',
        ];
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">DASHBOARD MONITORING</h2>

        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-file-earmark-text me-1"></i>
                Lihat Pengaduan
            </a>

            <a href="{{ route('pengerjaan.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-tools me-1"></i>
                Lihat Pengerjaan
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">

        @can('dashbord-menu.my-complaint-total')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Saya
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['total_pengaduan'] }}
                        </h1>

                        <i class="bi bi-clipboard-data position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.my-complaint-valid')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Valid
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['valid'] }}
                        </h1>

                        <i class="bi bi-check-circle position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.my-complaint-invalid')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Tidak Valid
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['tidak_valid'] }}
                        </h1>

                        <i class="bi bi-x-circle position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.my-complaint-complete')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Selesai
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['selesai'] }}
                        </h1>

                        <i class="bi bi-check2-circle position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.submission-total')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Pengajuan
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['pengajuan'] }}
                        </h1>

                        <i class="bi bi-send-check position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.complaint-valid')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Valid
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['valid'] }}
                        </h1>

                        <i class="bi bi-check-circle position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.complaint-invalid')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Tidak Valid
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['tidak_valid'] }}
                        </h1>

                        <i class="bi bi-x-circle position-absolute  top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.complaint-assigned')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Teknisi Ditugaskan
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['teknisi_ditugaskan'] }}
                        </h1>

                        <i class="bi bi-person-workspace position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.complaint-checking')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Dalam Pengecekan
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['dalam_pengecekan'] }}
                        </h1>

                        <i class="bi bi-search position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.complaint-working')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-secondary text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Dalam Pengerjaan
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['dalam_pengerjaan'] }}
                        </h1>

                        <i class="bi bi-tools position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.complaint-complete')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Selesai
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['selesai'] }}
                        </h1>

                        <i class="bi bi-check2-circle position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.complaint-total')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Total Pengaduan
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['total_pengaduan'] }}
                        </h1>

                        <i class="bi bi-clipboard-data-fill position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.technician-complaint-assigned')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Tugas Saya
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['teknisi_ditugaskan'] }}
                        </h1>

                        <i class="bi bi-person-badge position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.technician-complaint-checking')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Dalam Pengecekan
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['dalam_pengecekan'] }}
                        </h1>

                        <i class="bi bi-search position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.technician-complaint-working')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-secondary text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Dalam Pengerjaan
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['dalam_pengerjaan'] }}
                        </h1>

                        <i class="bi bi-tools position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.technician-complaint-complete')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengerjaan Selesai
                            <small>{{ auth()->user()->cabang->nama_cabang ?? '-' }}</small>
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['pengerjaan_selesai'] }}
                        </h1>

                        <i class="bi bi-check2-all position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.all-submission-total')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Pengajuan
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['pengajuan'] }}
                        </h1>

                        <i class="bi bi-send-check position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan


        @can('dashbord-menu.all-complaint-valid')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Valid
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['valid'] }}
                        </h1>

                        <i class="bi bi-check-circle position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.all-complaint-invalid')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Tidak Valid
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['tidak_valid'] }}
                        </h1>

                        <i class="bi bi-x-circle position-absolute  top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.all-complaint-assigned')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Teknisi Ditugaskan
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['teknisi_ditugaskan'] }}
                        </h1>

                        <i class="bi bi-person-workspace position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.all-complaint-checking')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Dalam Pengecekan
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['dalam_pengecekan'] }}
                        </h1>

                        <i class="bi bi-search position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan
        @can('dashbord-menu.all-complaint-working')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-secondary text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Dalam Pengerjaan
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['dalam_pengerjaan'] }}
                        </h1>

                        <i class="bi bi-tools position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.all-complaint-complete')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Pengaduan Selesai
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['selesai'] }}
                        </h1>

                        <i class="bi bi-check2-circle position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan

        @can('dashbord-menu.all-complaint-total')
            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark text-white border-0 shadow h-100 rounded-4">
                    <div class="card-body position-relative p-4">

                        <div class="text-uppercase fw-semibold opacity-75">
                            Total Pengaduan
                        </div>

                        <h1 class="fw-bold mb-0">
                            {{ $status['total_pengaduan'] }}
                        </h1>

                        <i class="bi bi-clipboard-data position-absolute top-0 end-0 m-4 fs-1 opacity-50"></i>

                    </div>
                </div>
            </div>
        @endcan
    </div>
    <div class="row g-4">

        {{-- Pengaduan Terbaru --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark-text text-danger me-2"></i>
                        Pengaduan Terbaru
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-nowrap">
                                <tr>
                                    <th>No Pengaduan</th>
                                    <th>Pelanggan</th>
                                    <th>Jenis Keluhan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPengaduan as $item)
                                    <tr>
                                        <td>{{ $item->nomor_pengaduan }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>{{ $item->jenis_keluhan ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $pengaduanBadgeMap[$item->status_pengaduan] ?? 'secondary' }}">
                                                {{ ucwords(str_replace('_', ' ', $item->status_pengaduan)) }}
                                            </span>
                                        </td>
                                        <td>{{ $item->tanggal_pengaduan?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('pengaduan.show', $item->pengaduan_id) }}"
                                                class="bi bi-eye btn btn-sm btn-outline-danger">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada pengaduan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monitoring Pengerjaan Teknisi --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-tools text-primary me-2"></i>
                        Monitoring Pengerjaan Teknisi
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-nowrap">
                                <tr>
                                    <th>No Pengaduan</th>
                                    <th>Teknisi</th>
                                    <th>Status</th>
                                    <th>Material</th>
                                    <th>Update Terakhir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPengerjaan as $item)
                                    <tr>
                                        <td>{{ $item->pengaduan->nomor_pengaduan ?? '-' }}</td>
                                        <td>{{ $item->teknisi->name ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $pengerjaanBadgeMap[$item->status_pengerjaan] ?? 'secondary' }}">
                                                {{ $item->status_pengerjaan ? ucwords(str_replace('_', ' ', $item->status_pengerjaan)) : 'Belum ada status' }}
                                            </span>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($item->material ?? '-', 40) }}</td>
                                        <td>{{ $item->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('pengerjaan.show', $item->pengerjaan_id) }}"
                                                class="bi bi-eye btn btn-sm btn-outline-primary">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada data pengerjaan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
