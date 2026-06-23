@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square text-primary me-2"></i>
            Edit Users
        </h2>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-person-fill text-warning me-2"></i>
                {{ $user->name }}
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('users.update', $user->user_id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nama
                        </label>

                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- NO HP --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            No HP
                        </label>

                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                            value="{{ old('no_hp', $user->no_hp) }}" required>

                        @error('no_hp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- CABANG --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Cabang
                        </label>

                        <select name="cabang_id" class="form-select @error('cabang_id') is-invalid @enderror">

                            <option value="">
                                -- Pilih Cabang --
                            </option>

                            @foreach ($cabang as $item)
                                <option value="{{ $item->cabang_id }}"
                                    {{ $user->cabang_id == $item->cabang_id ? 'selected' : '' }}>
                                    {{ $item->nama_cabang }}
                                </option>
                            @endforeach

                        </select>

                        @error('cabang_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ROLE --}}
                    @php
                        $userRole = $user->roles->first()?->name;
                    @endphp

                    @if (!$user->hasRole('pelanggan'))
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Role
                            </label>

                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>

                                <option value="manager" {{ $user->hasRole('manager') ? 'selected' : '' }}>
                                    Manager
                                </option>

                                <option value="customer_service" {{ $user->hasRole('customer_service') ? 'selected' : '' }}>
                                    Customer Service
                                </option>

                                <option value="koordinator_teknisi"
                                    {{ $user->hasRole('koordinator_teknisi') ? 'selected' : '' }}>
                                    Koordinator Teknisi
                                </option>

                                <option value="teknisi" {{ $user->hasRole('teknisi') ? 'selected' : '' }}>
                                    Teknisi
                                </option>

                                <option value="asisten_manager" {{ $user->hasRole('asisten_manager') ? 'selected' : '' }}>
                                    Asisten Manager
                                </option>

                                <option value="pelanggan" {{ $user->hasRole('pelanggan') ? 'selected' : '' }}>
                                    Pelanggan
                                </option>

                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="role" value="pelanggan">
                    @endif

                    {{-- PASSWORD --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Password Baru
                        </label>

                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Kosongkan jika tidak ingin mengubah">

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- STATUS AKTIF --}}
                    <div class="col-12">
                        <input type="hidden" name="is_active" value="0">

                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                                {{ $user->is_active ? 'checked' : '' }}>

                            <label class="form-check-label" for="is_active">
                                <span class="fw-semibold">User Aktif</span>
                            </label>
                        </div>
                    </div>


                </div>

                <hr class="my-4">

                {{-- BUTTON --}}
                <div class="d-flex flex-wrap justify-content-end gap-2">

                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i>
                        Update Users
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
