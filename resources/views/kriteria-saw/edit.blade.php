@extends('layouts.app')

@section('title', 'Edit Kriteria SAW')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square text-primary  me-2"></i>
            Edit Kriteria
        </h2>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square text-warning me-2"></i>
                {{ $kriteria->nama_kriteria }}
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('kriteria-saw.update', $kriteria->kriteria_saw_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- KODE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" class="form-control bg-light"
                            value="{{ old('kode_kriteria', $kriteria->kode_kriteria) }}" readonly>
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control"
                            value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" required>
                    </div>

                    {{-- BOBOT --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bobot (%)</label>
                        <input type="number" name="bobot" class="form-control"
                            value="{{ old('bobot', $kriteria->bobot) }}" min="1" max="{{ $sisaBobot }}" required>
                    </div>

                    {{-- JENIS --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenis Kriteria</label>
                        <select name="jenis" class="form-select" required>
                            <option value="benefit" {{ old('jenis', $kriteria->jenis) == 'benefit' ? 'selected' : '' }}>
                                Benefit
                            </option>
                            <option value="cost" {{ old('jenis', $kriteria->jenis) == 'cost' ? 'selected' : '' }}>
                                Cost
                            </option>
                        </select>
                    </div>

                </div>

                <hr class="my-4">

                {{-- BUTTON --}}
                <div class="d-flex flex-wrap justify-content-end gap-2">

                    <a href="{{ route('kriteria-saw.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-circle me-1"></i>
                        Update Kriteria
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
