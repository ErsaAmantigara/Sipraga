@extends('layouts.app')

@section('title', 'Role Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="fw-bold mb-0">
        <i class="bi bi-shield-lock text-primary me-2"></i>
        Manajemen Role
    </h2>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form method="GET" class="mb-3">

            <div class="row g-2">

                <div class="col-md-4">

                    <input type="text"
                           name="search"
                           class="form-control form-control-sm"
                           placeholder="Cari nama role..."
                           value="{{ $search ?? '' }}">

                </div>

                <div class="col-md-3">

                    <button type="submit"
                            class="btn btn-sm btn-outline-success">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>

                    @if(request('search'))
                        <a href="{{ route('roles.index') }}"
                           class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset
                        </a>
                    @endif

                </div>

            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light text-nowrap">
                    <tr>
                        <th>ID</th>
                        <th>Role</th>
                        <th>Guard</th>
                        <th>Permissions</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($roles as $role)

                        <tr>

                            <td>
                                <span class="badge bg-secondary">
                                    #{{ $role->id }}
                                </span>
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $role->name }}
                                </div>

                                @if($role->name === 'super-admin')
                                    <span class="badge bg-danger mt-1">
                                        System Role
                                    </span>
                                @endif

                            </td>

                            <td>

                                <span class="badge bg-dark">
                                    {{ $role->guard_name }}
                                </span>

                            </td>

                            <td>

                                <span class="badge bg-info">
                                    {{ $role->permissions->count() }}
                                    Permission
                                </span>

                            </td>

                            <td>

                                <small>
                                    {{ $role->created_at->format('d/m/Y') }}
                                </small>

                                <br>

                                <small class="text-muted">
                                    {{ $role->created_at->format('H:i') }}
                                </small>

                            </td>

                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-1 flex-wrap">

                                    <a href="{{ route('roles.show', $role->id) }}"
                                       class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @can('roles.edit')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                           class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan

                                    @can('roles.delete')
                                        @if($role->name !== 'super-admin')

                                            <form action="{{ route('roles.destroy', $role->id) }}"
                                                  method="POST"
                                                  class="form-delete">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        @endif
                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>

                                Belum ada data role

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($roles->count())

            <hr>

            {{ $roles->links() }}

        @endif

    </div>

</div>

@endsection