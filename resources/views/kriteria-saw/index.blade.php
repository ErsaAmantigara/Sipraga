@extends('layouts.app')

@section('title', 'Data Kriteria SAW')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            <i class="bi bi-list-check text-primary me-2"></i>
            Data Kriteria Prioritas
        </h2>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light text-nowrap">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Jenis</th>
                            @can('kriteria-saw.edit')
                                <th class="text-center">Aksi</th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($kriteria as $dataKriteria)
                            <tr>

                                {{-- KODE --}}
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $dataKriteria->kode_kriteria }}
                                    </span>
                                </td>

                                {{-- NAMA --}}
                                <td class="fw-semibold">
                                    {{ $dataKriteria->nama_kriteria }}
                                </td>

                                {{-- BOBOT --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">

                                        <div class="progress" style="height: 8px; width: 90px;">
                                            <div class="progress-bar" style="width: {{ $dataKriteria->bobot }}%;"></div>
                                        </div>

                                        <span class="fw-bold" style="min-width: 40px;">
                                            {{ $dataKriteria->bobot }}%
                                        </span>

                                    </div>
                                </td>

                                {{-- JENIS --}}
                                <td>
                                    <span class="badge bg-{{ $dataKriteria->jenis == 'benefit' ? 'success' : 'warning' }}">
                                        {{ ucfirst($dataKriteria->jenis) }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                @can('kriteria-saw.edit')
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-1 flex-wrap">

                                            <a href="{{ route('kriteria-saw.edit', $dataKriteria->kriteria_saw_id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        </div>

                                    </td>
                                @endcan

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada data kriteria
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <hr class="my-3">

            {{-- TOTAL BOBOT --}}
            <div class="d-flex flex-wrap align-items-center gap-3">

                <strong>Total Bobot</strong>

                <div class="progress" style="height: 10px; width: 220px;">
                    <div class="progress-bar bg-success" style="width: {{ $totalBobot }}%;"></div>
                </div>

                <span class="fw-bold">
                    {{ $totalBobot }}%
                </span>

                <small class="text-muted">
                    ({{ 100 - $totalBobot }}% tersedia)
                </small>

            </div>

        </div>

    </div>

    <hr>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-line-fill text-primary me-2"></i>
            Skala Penilaian Kriteria
        </h2>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">

            @foreach ($kriteria as $dataKriteria)
                <div class="card mb-4 border-0 shadow-sm">

                    {{-- HEADER --}}
                    <div class="card-header bg-light p-2">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <h6 class="mb-0 fw-bold">
                                    <span class="badge bg-primary me-1">
                                        {{ $dataKriteria->kode_kriteria }}
                                    </span>
                                    {{ $dataKriteria->nama_kriteria }}
                                </h6>

                                <small class="text-muted">
                                    Bobot : {{ $dataKriteria->bobot }}%
                                </small>
                            </div>

                        </div>

                    </div>

                    {{-- BODY TABLE --}}
                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light text-nowrap">
                                    <tr>
                                        <th width="25%">Sub Kriteria</th>
                                        <th>Deskripsi</th>
                                        <th width="10%">Nilai</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @php
                                        $subKriteriaMap = [
                                            'C1' => [
                                                ['nama' => 'Sangat Tinggi', 'deskripsi' => 'Pipa bocor', 'nilai' => 4],
                                                [
                                                    'nama' => 'Tinggi',
                                                    'deskripsi' => 'Pipa service, Rekening tagihan',
                                                    'nilai' => 3,
                                                ],
                                                [
                                                    'nama' => 'Sedang',
                                                    'deskripsi' =>
                                                        'Meter gas rusak, kompor tidak hidup, api kompor kecil',
                                                    'nilai' => 2,
                                                ],
                                                [
                                                    'nama' => 'Rendah',
                                                    'deskripsi' =>
                                                        'Perubahan instalasi pipa, Perubahan Instalasi Meteran, penambahan titik api, Instalasi Kompor Baru, Cabut Meteran (Stop Berlangganan)',
                                                    'nilai' => 1,
                                                ],
                                            ],
                                            'C2' => [
                                                [
                                                    'nama' => 'Sangat Lama',
                                                    'deskripsi' => 'Pelaporan >8 jam',
                                                    'nilai' => 4,
                                                ],
                                                ['nama' => 'Lama', 'deskripsi' => 'Pelaporan >6-8 jam', 'nilai' => 3],
                                                ['nama' => 'Sedang', 'deskripsi' => 'Pelaporan >3-6 jam', 'nilai' => 2],
                                                ['nama' => 'Baru', 'deskripsi' => 'Pelaporan 0-3 jam', 'nilai' => 1],
                                            ],
                                            'C3' => [
                                                ['nama' => 'PK2', 'deskripsi' => 'Usaha besar', 'nilai' => 4],
                                                ['nama' => 'PK1', 'deskripsi' => 'Usaha kecil', 'nilai' => 3],
                                                ['nama' => 'R2', 'deskripsi' => 'Rumah tangga mampu', 'nilai' => 2],
                                                [
                                                    'nama' => 'R1',
                                                    'deskripsi' => 'Rumah tangga kurang mampu',
                                                    'nilai' => 1,
                                                ],
                                            ],
                                            'C4' => [
                                                ['nama' => 'Jauh', 'deskripsi' => 'Jarak >6 km', 'nilai' => 4],
                                                ['nama' => 'Sedang', 'deskripsi' => 'Jarak >4-6 km', 'nilai' => 3],
                                                ['nama' => 'Dekat', 'deskripsi' => 'Jarak >2-4 km', 'nilai' => 2],
                                                ['nama' => 'Sangat Dekat', 'deskripsi' => 'Jarak 0-2 km', 'nilai' => 1],
                                            ],
                                        ];
                                        $items = $subKriteriaMap[$dataKriteria->kode_kriteria] ?? [];
                                    @endphp

                                    @forelse($items as $datasubkriteria)
                                        <tr>

                                            <td class="fw-semibold">
                                                {{ $datasubkriteria['nama'] }}
                                            </td>

                                            <td>
                                                {{ $datasubkriteria['deskripsi'] ?: '-' }}
                                            </td>

                                            <td>
                                                <span class="badge bg-info">
                                                    {{ number_format($datasubkriteria['nilai'], 2) }}
                                                </span>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Belum ada sub kriteria
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

@endsection
