<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\ProfilePelanggan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilePelangganController extends Controller
{
    public function edit(Request $request): View
    {
        $pelanggan = ProfilePelanggan::where('user_id', $request->user()->user_id)->firstOrFail();
        $cabang = Cabang::all();
        return view('profile-pelanggan.edit', [
            'pelanggan' => $pelanggan,
            'user' => $request->user(),
            'cabangs' => $cabang,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_id_pelanggan' => ['string', 'max:20'],
            'no_hp' => [
                'required',
                'string',
                'max:13',
            ],
            'jenis_pelanggan' => ['required', 'in:R1,R2,PK1,PK2'],
            'cabang_id' => ['required'],
            'alamat' => ['string', 'max:500'],
            'latitude' => ['numeric', 'between:-90,90'],
            'longitude' => ['numeric', 'between:-180,180'],
        ]);


        $request->user()->update([
            'name' => $validated['name'],
            'no_hp' => $validated['no_hp'],
        ]);

        ProfilePelanggan::where('user_id', $request->user()->user_id)->update([
            'no_id_pelanggan' => $validated['no_id_pelanggan'] ?? null,
            'jenis_pelanggan' => $validated['jenis_pelanggan'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        User::where('user_id', $request->user()->user_id)->update([
            'cabang_id' => $validated['cabang_id'] ?? null,
        ]);

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui');
    }


    private function authorizeProfilePelangganAccess(ProfilePelanggan $profilePelanggan): void
    {
        $user = auth()->user();

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan') && $profilePelanggan->user_id === $user->user_id) {
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $profilePelanggan->user?->cabang_id === $user->cabang_id) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke data pelanggan ini.');
    }


    private function authorizeBranchTarget(?int $cabangId): void
    {
        $user = auth()->user();

        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $cabangId === $user->cabang_id) {
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


    private function applyPelangganScope(Builder $query, User $user): void
    {
        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($user->hasRole('pelanggan')) {
            $query->where('user_id', $user->user_id);
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $user->cabang_id) {
            $query->whereHas('user', function ($userQuery) use ($user) {
                $userQuery->where('cabang_id', $user->cabang_id);
            });
        }
    }

    private function applyUserScope(Builder $query, User $user): void
    {
        if ($this->hasGlobalAccess($user)) {
            return;
        }

        if ($this->hasCabangScopedAccess($user) && $user->cabang_id) {
            $query->where('cabang_id', $user->cabang_id);
        }
    }
}
