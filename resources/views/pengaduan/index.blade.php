@extends('layouts.app')

@section('title', 'Data Pengaduan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            <i class="bi bi-file-earmark-text text-primary me-2"></i>
            Data Pengaduan
        </h2>

        @can('pengaduan.create')
        <a href="{{ route('pengaduan.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Buat Pengaduan
        </a>
        @endcan

    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari nomor pengaduan..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="pengajuan" {{ $status == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                            <option value="valid" {{ $status == 'valid' ? 'selected' : '' }}>Valid</option>
                            <option value="tidak_valid" {{ $status == 'tidak_valid' ? 'selected' : '' }}>Tidak Valid
                            </option>
                            <option value="teknisi_ditugaskan" {{ $status == 'teknisi_ditugaskan' ? 'selected' : '' }}>
                                Teknisi Ditugaskan</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                        @if(request()->anyFilled(['search', 'status']))
                            <a href="{{ route('pengaduan.index') }}" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th>No Pengaduan</th>
                            <th>No ID Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Jenis Keluhan</th>
                            <th>Status Pengaduan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengaduan as $datapengaduan)
                            <tr>
                                <td>{{ $datapengaduan->nomor_pengaduan }}</td>
                                <td>{{ $datapengaduan->user->profilepelanggan->no_id_pelanggan }}</td>
                                <td>{{ $datapengaduan->tanggal_pengaduan->format('d/m/Y H:i') }}</td>
                                <td>{{ $datapengaduan->user->name ?? '-' }}</td>
                                <td>{{ $datapengaduan->jenis_keluhan ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pengajuan' => 'warning',
                                            'valid' => 'info',
                                            'tidak_valid' => 'danger',
                                            'teknisi_ditugaskan' => 'primary',
                                            'selesai' => 'success',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$datapengaduan->status_pengaduan] }}">
                                        {{ ucwords(str_replace('_', ' ', $datapengaduan->status_pengaduan)) }}
                                    </span>
                                </td>
                                
                               <td class="align-middle">
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-1">

                                        <a href="{{ route('pengaduan.show', $datapengaduan->pengaduan_id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                            Detail
                                        </a>
                                      
                                    </div>
                                </td>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $pengaduan->links() }}
        </div>
    </div>
@endsection
