<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pengerjaan;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PengerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $user = auth()->user();

        $pengerjaan = Pengerjaan::with(['pengaduan.user', 'teknisi']);
        $this->applyPengerjaanScope($pengerjaan, $user);

        $pengerjaan = $pengerjaan
            ->when($status, function ($query) use ($status) {
                $query->where('status_pengerjaan', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pengerjaan.index', compact('pengerjaan', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengaduan_id' => 'required|exists:pengaduan,pengaduan_id|unique:pengerjaan,pengaduan_id',
            'user_id' => 'required|exists:users,user_id',
            'keterangan_teknisi' => 'nullable',
            'material' => 'nullable',
        ]);

        $pengaduan = Pengaduan::with('user')->findOrFail($validated['pengaduan_id']);
        $teknisi = User::findOrFail($validated['user_id']);

        $this->authorizeCabangTarget($pengaduan->user?->cabang_id);
        $this->authorizeCabangTarget($teknisi->cabang_id);

        $validated['tanggal_mulai'] = null;
        $validated['status_pengerjaan'] = null;

        Pengerjaan::create($validated);

        return redirect()->route('pengerjaan.index')
            ->with('success', 'Pengerjaan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengerjaan $pengerjaan)
    {
        $this->authorizePengerjaanAccess($pengerjaan);
        $pengerjaan->load(['pengaduan.user.profilepelanggan', 'teknisi']);

        return view('pengerjaan.show', compact('pengerjaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengerjaan $pengerjaan)
    {
        $this->authorizeTeknisiPengerjaan($pengerjaan);
        $pengerjaan->load(['pengaduan.user.profilepelanggan', 'teknisi']);

        return view('pengerjaan.edit', compact('pengerjaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengerjaan $pengerjaan)
    {
        $this->authorizeTeknisiPengerjaan($pengerjaan);

        $validated = $request->validate([
            'keterangan_teknisi' => 'nullable',
            'material' => 'nullable',
            'foto_sebelum' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_proses' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_sesudah' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $uploadFields = ['foto_sebelum', 'foto_proses', 'foto_sesudah'];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                if ($pengerjaan->$field) {
                    \Storage::disk('public')->delete('pengerjaan/' . $pengerjaan->$field);
                }

                $file = $request->file($field);
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('pengerjaan', $filename, 'public');
                $validated[$field] = $filename;
            }
        }

        $statusPengerjaan = $this->determineStatusPengerjaan($pengerjaan, $validated);
        $validated['status_pengerjaan'] = $statusPengerjaan;

        $hasFotoSebelumNow = $request->hasFile('foto_sebelum');
        $hasFotoProsesNow = $request->hasFile('foto_proses');
        $hasFotoSesudahNow = $request->hasFile('foto_sesudah');

        $willHaveFotoSebelum = $hasFotoSebelumNow || $pengerjaan->foto_sebelum !== null;
        $willHaveFotoProses = $hasFotoProsesNow || $pengerjaan->foto_proses !== null;
        $willHaveFotoSesudah = $hasFotoSesudahNow || $pengerjaan->foto_sesudah !== null;

        if ($hasFotoProsesNow && !$willHaveFotoSebelum) {
            throw ValidationException::withMessages([
                'foto_proses' => 'Upload foto sebelum terlebih dahulu sebelum foto proses.',
            ]);
        }

        if ($hasFotoSesudahNow && !$willHaveFotoProses) {
            throw ValidationException::withMessages([
                'foto_sesudah' => 'Upload foto proses terlebih dahulu sebelum foto sesudah.',
            ]);
        }

        $submittedMaterial = trim((string) ($validated['material'] ?? ''));
        $existingMaterial = trim((string) ($pengerjaan->material ?? ''));
        $hasFotoSebelumDb = $pengerjaan->foto_sebelum !== null;

        if ($submittedMaterial !== '' && !$hasFotoSebelumDb) {
            throw ValidationException::withMessages([
                'material' => 'Upload foto sebelum terlebih dahulu sebelum mengisi material.',
            ]);
        }

        if ($hasFotoProsesNow && $existingMaterial === '') {
            throw ValidationException::withMessages([
                'foto_proses' => 'Isi material terlebih dahulu sebelum upload foto proses.',
            ]);
        }

        if ($hasFotoSesudahNow && $existingMaterial === '') {
            throw ValidationException::withMessages([
                'foto_sesudah' => 'Material wajib diisi sebelum upload foto sesudah.',
            ]);
        }

        if ($statusPengerjaan && !$pengerjaan->tanggal_mulai) {
            $validated['tanggal_mulai'] = now();
        }

        if ($willHaveFotoSesudah && !$pengerjaan->tanggal_selesai) {
            $validated['tanggal_selesai'] = now();
            $pengerjaan->pengaduan?->update([
                'status_pengaduan' => 'selesai',
                'tanggal_selesai' => now(),
            ]);
        } elseif (!$willHaveFotoSesudah) {
            $validated['tanggal_selesai'] = null;
        }

        $pengerjaan->update($validated);

        return redirect()->route('pengerjaan.edit', $pengerjaan->pengerjaan_id)
            ->with('success', 'Pengerjaan berhasil diperbarui');
    }

    /**
     * Beri rating
     */
    public function rating(Request $request, Pengerjaan $pengerjaan)
    {
        $this->authorizeRating($pengerjaan);

        $validated = $request->validate([
            'rating_nilai' => 'required|integer|min:1|max:5',
            'rating_komentar' => 'nullable|string',
        ]);

        $validated['tanggal_rating'] = now();

        $pengerjaan->update($validated);

        return redirect()->route('pengerjaan.show', $pengerjaan->pengerjaan_id)
            ->with('success', 'Rating berhasil diberikan');
    }

    private function authorizePengerjaanAccess(Pengerjaan $pengerjaan): void
    {
        $user = auth()->user();

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan') && $pengerjaan->pengaduan?->user_id === $user->user_id) {
            return;
        }

        if ($user->hasRole('teknisi') && $pengerjaan->user_id === $user->user_id) {
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $pengerjaan->pengaduan?->user?->cabang_id === $user->cabang_id) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke pengerjaan ini.');
    }

    private function authorizeTeknisiPengerjaan(Pengerjaan $pengerjaan): void
    {
        $user = auth()->user();

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('teknisi') && $pengerjaan->user_id !== $user->user_id) {
            abort(403, 'Anda hanya dapat mengubah pengerjaan yang ditugaskan kepada Anda.');
        }

        if ($this->hasCabangScopedAccess($user) && $pengerjaan->pengaduan?->user?->cabang_id !== $user->cabang_id) {
            abort(403, 'Anda tidak dapat mengubah pengerjaan di luar cabang Anda.');
        }
    }

    private function authorizeRating(Pengerjaan $pengerjaan): void
    {
        $user = auth()->user();

        if (! $user->hasRole('pelanggan')) {
            abort(403, 'Hanya pelanggan yang dapat memberikan rating.');
        }

        if ($pengerjaan->pengaduan?->user_id !== $user->user_id) {
            abort(403, 'Anda hanya dapat memberikan rating untuk pengaduan milik sendiri.');
        }

        if ($pengerjaan->status_pengerjaan !== 'selesai') {
            abort(422, 'Rating hanya bisa diberikan setelah pengerjaan selesai.');
        }
    }

    private function applyPengerjaanScope(Builder $query, User $user): void
    {
        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan')) {
            $query->whereHas('pengaduan', function ($pengaduanQuery) use ($user) {
                $pengaduanQuery->where('user_id', $user->user_id);
            });
            return;
        }

        if ($user->hasRole('teknisi')) {
            $query->where('user_id', $user->user_id);
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $user->cabang_id) {
            $query->whereHas('pengaduan.user', function ($userQuery) use ($user) {
                $userQuery->where('cabang_id', $user->cabang_id);
            });
        }
    }

    private function applyPengaduanBranchScope(Builder $query, User $user): void
    {
        if ($this->hasCabangScopedAccess($user) && $user->cabang_id) {
            $query->whereHas('user', function ($userQuery) use ($user) {
                $userQuery->where('cabang_id', $user->cabang_id);
            });
        }
    }

    private function authorizeCabangTarget(?int $cabangId): void
    {
        $user = auth()->user();

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $cabangId === $user->cabang_id) {
            return;
        }

        if ($user->hasRole('teknisi') && $cabangId === $user->cabang_id) {
            return;
        }

        abort(403, 'Anda tidak dapat mengelola data di luar cabang Anda.');
    }

    private function hasGlobalAccess(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'manager']);
    }

    private function hasCabangScopedAccess(User $user): bool
    {
        return $user->hasAnyRole(['customer_service', 'koordinator_teknisi', 'asisten_manager']);
    }

    private function determineStatusPengerjaan(Pengerjaan $pengerjaan, array $validated): ?string
    {
        $hasFotoSebelum = ($validated['foto_sebelum'] ?? $pengerjaan->foto_sebelum) !== null;
        $hasFotoProses = ($validated['foto_proses'] ?? $pengerjaan->foto_proses) !== null;
        $hasFotoSesudah = ($validated['foto_sesudah'] ?? $pengerjaan->foto_sesudah) !== null;

        if ($hasFotoSesudah) {
            return 'selesai';
        }

        if ($hasFotoProses) {
            return 'dalam_pengerjaan';
        }

        if ($hasFotoSebelum) {
            return 'dalam_pengecekan';
        }

        return null;
    }
}
