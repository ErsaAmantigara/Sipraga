@extends('layouts.app')

@section('title', 'Penilaian SAW')

@section('content')

    @php
        $scoreBadgeMap = [
            'Sangat Tinggi' => 'danger',
            'Tinggi' => 'warning',
            'Sedang' => 'info',
            'Rendah' => 'secondary',
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-clipboard-data text-primary me-2"></i>
            Penilaian Prioritas Pengaduan
        </h2>

        <form action="{{ route('penilaian-saw.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sync me-1"></i> Generate Penilaian
            </button>
        </form>
    </div>

    {{-- DATA ALTERNATIF --}}
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul text-primary me-2"></i>
                Data Alternatif
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Pengaduan</th>
                            <th>Jenis Keluhan</th>

                            @foreach ($fieldMap as $config)
                                <th>{{ $config['kriteria']->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengaduan as $item)
                            <tr>
                                <td class="align-middle">
                                    <strong>
                                        {{ $item->nomor_pengaduan }}
                                    </strong>
                                </td>

                                <td class="align-middle">{{ $item->jenis_keluhan ?? '-' }}</td>

                                @foreach ($item->criteria_summary as $summary)
                                    <td>
                                        @if ($summary['value'] !== null)
                                            {{ number_format($summary['value'], 0) }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $summary['sub_label'] ?? '-' }}
                                            </small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($fieldMap) + 2 }}" class="text-center">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{-- NORMALISASI --}}
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-calculator text-success me-2"></i>
                Normalisasi Matriks Keputusan
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Pengaduan</th>

                            @foreach ($fieldMap as $config)
                                <th>{{ $config['kriteria']->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($normalisasiRows as $item)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $item->nomor_pengaduan }}
                                        </strong>
                                    </td>

                                    @foreach ($fieldMap as $config)
                                        <td>
                                            {{ number_format((float) ($item->penilaianSaw->{$config['normalisasi_field']} ?? 0), 4) }}
                                        </td>
                                    @endforeach
                                </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($fieldMap) + 1 }}" class="text-center">
                                    Tidak ada data normalisasi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{-- NILAI PREFERENSI --}}
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-bar-chart text-warning me-2"></i>
                Kalkulasi Nilai Preferensi
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Pengaduan</th>
                            <th>Jenis Keluhan</th>
                            <th>Nilai Tertinggi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rankedRows as $item)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $item->nomor_pengaduan }}
                                    </strong>
                                </td>

                                <td>{{ $item->jenis_keluhan }}</td>

                                <td>
                                    <strong>
                                        {{ number_format((float) $item->penilaianSaw->nilai_preferensi, 4) }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{-- RANKING --}}
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-trophy text-danger me-2"></i>
                Ranking Hasil SAW
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ranking</th>
                            <th>No Pengaduan</th>
                            <th>Jenis Keluhan</th>
                            <th>Nilai Tertinggi</th>
                            <th>Prioritas</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rankedRows as $item)
                            <tr style="cursor:pointer"
                                onclick="window.location='{{ route('pengaduan.show', $item->pengaduan_id) }}'">

                                <td>
                                    <span class="badge bg-dark">
                                        #{{ $item->penilaianSaw->ranking }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        {{ $item->nomor_pengaduan }}
                                    </strong>
                                </td>

                                <td>{{ $item->jenis_keluhan }}</td>

                                <td>
                                    {{ number_format((float) $item->penilaianSaw->nilai_preferensi, 4) }}
                                </td>

                                <td>
                                    <span
                                        class="badge bg-{{ $scoreBadgeMap[$item->penilaianSaw->kategori_prioritas] ?? 'secondary' }}">
                                        {{ $item->penilaianSaw->kategori_prioritas }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    Belum ada ranking
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $pengaduan->links() }}
    </div>

@endsection
