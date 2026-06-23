@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="fw-bold mb-0">
        <i class="bi bi-bar-chart-line text-primary me-2"></i>
        Laporan Bulanan
    </h2>

</div>

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <form method="GET">

            <div class="row g-2 align-items-end">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Pilih Bulan
                    </label>

                    <input type="month"
                           name="month"
                           class="form-control form-control-sm"
                           value="{{ $month }}"
                           required>

                </div>

                <div class="col-md-3">

                    <button type="submit"
                            class="btn btn-sm btn-outline-success">
                        <i class="bi bi-search me-1"></i>
                        Tampilkan
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="row g-4">

    @can('laporan-pengaduan.view')

    <div class="col-lg-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                    Laporan Pengaduan
                </h5>

            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <small class="text-muted d-block">
                            Total Pengaduan
                        </small>

                        <h3 class="fw-bold text-primary mb-0">
                            {{ $pengaduanCount }}
                        </h3>

                    </div>

                    <i class="bi bi-clipboard-data fs-1 text-primary opacity-50"></i>

                </div>

                <p class="text-muted mb-3">
                    Data pengaduan bulan
                    <strong>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                    </strong>
                </p>

                <a href="{{ route('laporan.pengaduan', ['month' => $month]) }}"
                   class="btn btn-success">

                    <i class="bi bi-download me-1"></i>
                    Download Excel

                </a>

            </div>

        </div>

    </div>

    @endcan

    @can('laporan-pengerjaan.view')

    <div class="{{ auth()->user()->can('laporan-pengaduan.view') ? 'col-lg-6' : 'col-lg-12' }}">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-tools text-success me-2"></i>
                    Laporan Pengerjaan
                </h5>

            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <small class="text-muted d-block">
                            Total Pengerjaan
                        </small>

                        <h3 class="fw-bold text-success mb-0">
                            {{ $pengerjaanCount }}
                        </h3>

                    </div>

                    <i class="bi bi-gear-wide-connected fs-1 text-success opacity-50"></i>

                </div>

                <p class="text-muted mb-3">
                    Data pengerjaan bulan
                    <strong>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                    </strong>
                </p>

                <a href="{{ route('laporan.pengerjaan', ['month' => $month]) }}"
                   class="btn btn-success">

                    <i class="bi bi-download me-1"></i>
                    Download Excel

                </a>

            </div>

        </div>

    </div>

    @endcan

</div>

@endsection