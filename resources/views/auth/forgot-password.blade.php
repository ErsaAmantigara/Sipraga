@extends('layouts.app')

@section('content')
<div style="width: 100%; height: 100vh; background: linear-gradient(135deg, #020617 0%, #071938 35%, #0a2a63 70%, #123c7a 100%);" class="d-flex justify-content-center align-items-center">
    <div class="d-flex justify-content-center align-items-center px-3 py-4">
    
        <div class="card border-0 shadow-lg rounded-4 p-4">
    
            {{-- Logo --}}
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow mb-3"
                    style="width: 100px; height: 100px;">
    
                    <img src="{{ asset('images/logo.jpg') }}"
                        alt="Logo"
                        style="width: 90px; height: 90px; object-fit: contain;">
                </div>
    
                <h3 class="fw-bold mb-1">
                    Lupa Password
                </h3>
    
                <p class="text-muted small mb-0">
                    PT. Sarana Pembangunan Palembang Jaya
                </p>
            </div>
    
            {{-- Deskripsi --}}
            <div class="text-center mb-2">
                <p class="text-muted small mb-0">
                    Masukkan nomor HP akun Anda untuk menerima OTP reset password melalui WhatsApp.
                </p>
            </div>
    
            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
    
                {{-- Nomor HP --}}
                <div class="mb-4">
                    <label for="no_hp" class="form-label fw-semibold">
                        Nomor HP
                    </label>
    
                    <input
                        id="no_hp"
                        type="number"
                        name="no_hp"
                        value="{{ old('no_hp') }}"
                        class="form-control rounded-3 @error('no_hp') is-invalid @enderror"
                        placeholder="081234567890"
                        required
                        autofocus
                    >
    
                    @error('no_hp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
    
                {{-- Button --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary rounded-3 fw-semibold py-2">
                        Kirim OTP
                    </button>
                </div>
    
                {{-- Back Login --}}
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none small">
                        ← Kembali ke Login
                    </a>
                </div>
            </form>
    
        </div>
    
    </div>
</div>
@endsection