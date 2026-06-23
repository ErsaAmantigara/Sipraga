@extends('layouts.app')

@section('title', 'Data Pengerjaan')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            <i class="bi bi-tools text-primary me-2"></i>
            Data Pengerjaan
        </h2>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            {{-- FILTER --}}
            <form method="GET" class="mb-3">

                <div class="row g-2">

                    <div class="col-md-3">

                        <select name="status" class="form-select form-select-sm">

                            <option value="">-- Semua Status --</option>

                            <option value="dalam_pengecekan"
                                {{ request('status') == 'dalam_pengecekan' ? 'selected' : '' }}>
                                Dalam Pengecekan
                            </option>

                            <option value="dalam_pengerjaan"
                                {{ request('status') == 'dalam_pengerjaan' ? 'selected' : '' }}>
                                Dalam Pengerjaan
                            </option>

                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-search me-1"></i>
                            Filter
                        </button>

                        @if (request()->filled('status'))
                            <a href="{{ route('pengerjaan.index') }}" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset
                            </a>
                        @endif

                    </div>

                </div>

            </form>

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light text-nowrap">
                        <tr>
                            <th>Teknisi</th>
                            <th>No Pengaduan</th>
                            <th>No ID Pelanggan</th>
                            <th>Jenis Keluhan</th>
                            <th>No HP</th>
                            <th>Status Pengerjaan</th>
                            <th>Rating</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pengerjaan as $datapengerjaan)
                            <tr>

                                <td>
                                    {{ $datapengerjaan->teknisi->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $datapengerjaan->pengaduan->nomor_pengaduan }}
                                </td>

                                <td>
                                    {{ $datapengerjaan->pengaduan->user->profilepelanggan->no_id_pelanggan ?? '-' }}
                                </td>

                                <td>
                                    {{ $datapengerjaan->pengaduan->jenis_keluhan }}
                                </td>

                                <td>
                                    {{ $datapengerjaan->pengaduan->user->no_hp ?? '-' }}
                                </td>

                                <td>

                                    @php
                                        $statusColor = match ($datapengerjaan->status_pengerjaan) {
                                            'dalam_pengecekan' => 'warning',
                                            'dalam_pengerjaan' => 'primary',
                                            'selesai' => 'success',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    @if ($datapengerjaan->status_pengerjaan)
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucwords(str_replace('_', ' ', $datapengerjaan->status_pengerjaan)) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Belum Ada Status
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    @if ($datapengerjaan->rating_nilai)
                                        <span class="badge bg-success">
                                            {{ $datapengerjaan->rating_nilai }}/5
                                        </span>
                                    @else
                                        -
                                    @endif

                                </td>

                                <td class="align-middle">
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-1">
                                        <a href="{{ route('pengerjaan.show', $datapengerjaan->pengerjaan_id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>

                                        @can('pengerjaan.edit')
                                            <a href="{{ route('pengerjaan.edit', $datapengerjaan->pengerjaan_id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-tools"></i> Pengerjaan
                                            </a>
                                        @endcan
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    Tidak ada data pengerjaan
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $pengerjaan->links() }}
            </div>

        </div>

    </div>

@endsection
