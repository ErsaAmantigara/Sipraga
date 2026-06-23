@extends('layouts.app')

@section('title', 'Detail Role')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="fw-bold mb-0">
        <i class="bi bi-shield-lock text-primary me-2"></i>
        Detail Role
    </h2>

    <div class="d-flex gap-2">

        @can('roles.edit')
            <a href="{{ route('roles.edit', $role->id) }}"
               class="btn btn-warning btn-sm">
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>
        @endcan

        <a href="{{ route('roles.index') }}"
           class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

</div>

<div class="row g-4">

    {{-- INFORMASI ROLE --}}
    <div class="col-lg-5">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-person-badge text-primary me-2"></i>
                    Informasi Role
                </h5>

            </div>

            <div class="card-body">

                <div class="mb-3">
                    <small class="text-muted d-block">
                        ID Role
                    </small>

                    <span class="badge bg-secondary">
                        #{{ $role->id }}
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Nama Role
                    </small>

                    <div class="fw-bold fs-5">
                        {{ $role->name }}
                    </div>

                    @if($role->name === 'super-admin')
                        <span class="badge bg-danger mt-1">
                            System Role
                        </span>
                    @endif
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Guard
                    </small>

                    <span class="badge bg-dark">
                        {{ $role->guard_name }}
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">
                        Dibuat
                    </small>

                    <div>
                        {{ $role->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div>
                    <small class="text-muted d-block">
                        Diperbarui
                    </small>

                    <div>
                        {{ $role->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- PERMISSIONS --}}
    <div class="col-lg-7">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="bi bi-key-fill text-success me-2"></i>
                    Permissions
                </h5>

                <span class="badge bg-success">
                    {{ $role->permissions->count() }} Permission
                </span>

            </div>

            <div class="card-body">

                @if($role->permissions->count())

                    <div class="d-flex flex-wrap gap-2">

                        @foreach($role->permissions as $permission)

                            <span class="badge bg-primary px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ $permission->name }}
                            </span>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-5 text-muted">

                        <i class="bi bi-shield-x fs-1 d-block mb-2"></i>

                        Belum ada permission yang diberikan.

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection