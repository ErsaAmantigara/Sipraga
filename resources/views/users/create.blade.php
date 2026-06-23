@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-plus text-primary me-2"></i>
                Form Tambah Users
            </h2>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="bi bi-person-fill-add text-success me-2"></i>
                Tambah Users Baru
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nama Lengkap
                        </label>

                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>

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
                            value="{{ old('no_hp') }}" required>

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

                            <option value="">-- Pilih Cabang --</option>

                            @foreach ($cabang as $item)
                                <option value="{{ $item->cabang_id }}"
                                    {{ old('cabang_id') == $item->cabang_id ? 'selected' : '' }}>
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
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Role
                        </label>

                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>

                            <option value="">-- Pilih Role --</option>

                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                                Manager
                            </option>

                            <option value="customer_service" {{ old('role') == 'customer_service' ? 'selected' : '' }}>
                                Customer Service
                            </option>

                            <option value="koordinator_teknisi"
                                {{ old('role') == 'koordinator_teknisi' ? 'selected' : '' }}>
                                Koordinator Teknisi
                            </option>

                            <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>
                                Teknisi
                            </option>

                            <option value="asisten_manager" {{ old('role') == 'asisten_manager' ? 'selected' : '' }}>
                                Asisten Manager
                            </option>

                        </select>

                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- PASSWORD --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Password
                        </label>

                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
                        Simpan Users
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
