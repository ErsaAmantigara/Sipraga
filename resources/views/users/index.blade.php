@extends('layouts.app')

@section('title', 'Data Users')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="fw-bold mb-0">
        <i class="bi bi-people text-primary me-2"></i>
        Data Users
    </h2>

    @can('users.create')
        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah User
        </a>
    @endcan

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        {{-- SEARCH --}}
        <form method="GET" class="mb-3">

            <div class="row g-2">

                <div class="col-md-4">
                    <input type="text"
                        name="search"
                        class="form-control form-control-sm"
                        placeholder="Cari nama atau no HP..."
                        value="{{ $search ?? '' }}">
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>

                    @if(request()->filled('search'))
                        <a href="{{ route('users.index') }}"
                            class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    @endif
                </div>

            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-hover">

                <thead class="table-light text-nowrap">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Cabang</th>
                        <th>Tanggal Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $i => $user)

                        <tr>

                            <td>
                                {{ $users->firstItem() + $i }}
                            </td>

                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->no_hp }}
                            </td>

                            <td>
                                @forelse($user->getRoleNames() as $role)
                                    <span class="badge bg-primary me-1">
                                        {{ $role }}
                                    </span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </td>

                            <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td>
                                {{ $user->cabang?->nama_cabang ?? '-' }}
                            </td>

                            <td>
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>

                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-1 flex-wrap">

                                    @can('users.edit')
                                        <a href="{{ route('users.edit', $user->user_id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"> Edit</i>
                                        </a>
                                    @endcan

                                    @can('users.delete')
                                        <form action="{{ route('users.destroy', $user->user_id) }}"
                                            method="POST"
                                            class="form-delete">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"> Hapus</i>
                                            </button>

                                        </form>
                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8"
                                class="text-center py-4 text-muted">
                                Belum ada data user
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
            {{ $users->links() }}
        </div>

    </div>

</div>

@endsection