@extends('layouts.app')

@section('title', 'Tambah Role')

@section('content')
<div class="card">
    <div class="card-header"> 
        <h4>Tambah Role Baru</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Role <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: admin, teknisi, pelanggan">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Guard</label>
                <select name="guard_name" class="form-control">
                    <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Web</option>
                    <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                </select>
                @error('guard_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Permissions</label>
                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" onclick="toggleAllPermissions(true)">Pilih Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary mb-2" onclick="toggleAllPermissions(false)">Hapus Semua</button>
                    </div>

                    @foreach($permissions as $group => $groupPermissions)
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input permission-group" id="group_{{ $group }}" onchange="toggleGroupPermissions('{{ $group }}')">
                            <label class="form-check-label fw-bold" for="group_{{ $group }}">{{ ucfirst($group) }}</label>
                        </div>
                        <div class="ms-4 mt-2">
                            @foreach($groupPermissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" class="form-check-input permission-item permission-group-{{ $group }}" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    @if($permissions->isEmpty())
                    <p class="text-muted">Belum ada permissions. Pastikan permissions sudah dibuat.</p>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleGroupPermissions(group) {
    const groupCheckbox = document.getElementById('group_' + group);
    const checkboxes = document.querySelectorAll('.permission-group-' + group);
    checkboxes.forEach(cb => cb.checked = groupCheckbox.checked);
}

function toggleAllPermissions(selectAll) {
    document.querySelectorAll('.permission-item').forEach(cb => cb.checked = selectAll);
    document.querySelectorAll('.permission-group').forEach(cb => cb.checked = selectAll);
}
</script>
@endpush
@endsection