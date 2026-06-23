@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="fw-bold mb-0">
        <i class="bi bi-pencil-square text-warning me-2"></i>
        Edit Role
    </h2>

    <a href="{{ route('roles.index') }}"
       class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>
        Kembali
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            <i class="bi bi-shield-lock text-primary me-2"></i>
            {{ $role->name }}
        </h5>

    </div>

    <div class="card-body">

        <form action="{{ route('roles.update', $role->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            {{-- Nama Role --}}
            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Nama Role
                    <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $role->name) }}"
                       required>

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Guard --}}
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Guard
                </label>

                <select name="guard_name"
                        class="form-select @error('guard_name') is-invalid @enderror">

                    <option value="web"
                        {{ old('guard_name', $role->guard_name) == 'web' ? 'selected' : '' }}>
                        Web
                    </option>

                    <option value="api"
                        {{ old('guard_name', $role->guard_name) == 'api' ? 'selected' : '' }}>
                        API
                    </option>

                </select>

                @error('guard_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Permissions --}}
            <div class="mb-4">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <label class="form-label fw-semibold mb-0">
                        Permissions
                    </label>

                    <span class="badge bg-primary">
                        {{ $permissions->flatten()->count() }} Permission
                    </span>

                </div>

                <div class="border rounded p-3 bg-light"
                     style="max-height:500px; overflow-y:auto;">

                    <div class="mb-3">

                        <button type="button"
                                class="btn btn-success btn-sm"
                                onclick="toggleAllPermissions(true)">
                            <i class="bi bi-check-all me-1"></i>
                            Pilih Semua
                        </button>

                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="toggleAllPermissions(false)">
                            <i class="bi bi-x-circle me-1"></i>
                            Hapus Semua
                        </button>

                    </div>

                    @php
                        $rolePermissions = $role->permissions->pluck('id')->toArray();
                    @endphp

                    @forelse($permissions as $group => $groupPermissions)

                        <div class="card border-0 shadow-sm mb-3">

                            <div class="card-header bg-white">

                                <div class="form-check mb-0">

                                    <input type="checkbox"
                                           class="form-check-input permission-group"
                                           id="group_{{ $group }}"
                                           onchange="toggleGroupPermissions('{{ $group }}')">

                                    <label class="form-check-label fw-bold"
                                           for="group_{{ $group }}">
                                        {{ ucfirst($group) }}
                                    </label>

                                </div>

                            </div>

                            <div class="card-body py-2">

                                <div class="row">

                                    @foreach($groupPermissions as $permission)

                                        <div class="col-md-6">

                                            <div class="form-check mb-2">

                                                <input type="checkbox"
                                                       id="permission_{{ $permission->id }}"
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       class="form-check-input permission-item permission-group-{{ $group }}"
                                                       {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>

                                                <label class="form-check-label"
                                                       for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="alert alert-warning mb-0">
                            Belum ada permission yang tersedia.
                        </div>

                    @endforelse

                </div>

            </div>

            <hr>

            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Update Role
                </button>

                <a href="{{ route('roles.index') }}"
                   class="btn btn-secondary">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@push('scripts')
<script>
function toggleGroupPermissions(group)
{
    const groupCheckbox =
        document.getElementById('group_' + group);

    document
        .querySelectorAll('.permission-group-' + group)
        .forEach(cb => cb.checked = groupCheckbox.checked);
}

function toggleAllPermissions(selectAll)
{
    document
        .querySelectorAll('.permission-item')
        .forEach(cb => cb.checked = selectAll);

    document
        .querySelectorAll('.permission-group')
        .forEach(cb => cb.checked = selectAll);
}
</script>
@endpush

@endsection