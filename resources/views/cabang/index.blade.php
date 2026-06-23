@extends('layouts.app')

@section('title', 'Data Cabang')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="fw-bold mb-0">
        <i class="bi bi-buildings text-primary me-2"></i>
        Data Cabang
    </h2>

    @can('cabang.create')
        <a href="{{ route('cabang.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Cabang
        </a>
    @endcan

</div>

<div class="card">
    <div class="card-body">

        {{-- Table --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Nama Cabang</th>
                        <th>Alamat Lengkap</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($cabang as $datacabang)
                        <tr>

                            <td>
                                {{ $loop->iteration + ($cabang->currentPage() - 1) * $cabang->perPage() }}
                            </td>

                            <td class="fw-semibold">
                                {{ $datacabang->nama_cabang }}
                            </td>

                            <td>
                                {{ $datacabang->alamat }}
                            </td>

                            <td>
                                <small>{{ $datacabang->latitude }}</small>
                            </td>

                            <td>
                                <small>{{ $datacabang->longitude }}</small>
                            </td>

                            <td>
                                <span class="badge bg-{{ $datacabang->is_active ? 'success' : 'danger' }}">
                                    {{ $datacabang->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                             <td class="text-center">

                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    
                                    @can('cabang.edit')
                                        <a href="{{ route('cabang.edit', $datacabang) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"> Edit</i>
                                        </a>
                                    @endcan

                                    @can('cabang.delete')
                                        <form action="{{ route('cabang.destroy', $datacabang) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger btn-delete">
                                                <i class="bi bi-trash"> Hapus</i>
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                Belum ada data cabang
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $cabang->links() }}
        </div>

    </div>
</div>

@endsection