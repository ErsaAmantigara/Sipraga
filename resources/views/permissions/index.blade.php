@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Permission Management</h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari permission..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary">Cari</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Permission</th>
                        <th>Guard</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                    <tr>
                        <td>
                            <code>{{ $permission->name }}</code>
                        </td>
                        <td><span class="badge bg-secondary">{{ $permission->guard_name }}</span></td>
                        <td>{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $permissions->links() }}
    </div>
</div>
@endsection