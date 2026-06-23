<?php

namespace App\Http\Controllers;

use App\Models\ProfilePelanggan;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $users = User::with(['roles', 'cabang'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->orderBy('name', 'asc');

        $this->applyUserScope($users, auth()->user());

        $users = $users->paginate(10);

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        $cabang = $this->getAvailableCabangs();
        return view('users.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:35',
            'no_hp' => 'required|string|max:13|unique:users,no_hp',
            'password' => 'required|string|min:6',
            'is_active' => 'nullable|boolean',
            'role' => 'required|in:admin,manager,customer_service,koordinator_teknisi,teknisi,asisten_manager,pelanggan',
            'cabang_id' => 'nullable|exists:cabang,cabang_id',
        ]);

        $this->authorizeRoleBranchRules($validated['role'], $validated['cabang_id'] ?? null);
        $this->ensureSingleBranchRoleAvailability($validated['role'], $validated['cabang_id'] ?? null);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'no_hp' => $validated['no_hp'],
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['is_active'] ?? true,
                'cabang_id' => $validated['cabang_id'] ?? null,
            ]);

            $user->assignRole($validated['role']);

            if ($validated['role'] === 'pelanggan') {
                ProfilePelanggan::create([
                    'user_id' => $user->user_id,
                    'no_id_pelanggan' => 'PLG-' . str_pad((string) $user->user_id, 4, '0', STR_PAD_LEFT),
                    'jenis_pelanggan' => 'R1',
                    'alamat' => '-',
                    'latitude' => null,
                    'longitude' => null,
                ]);
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $this->authorizeUserAccess($user);
        $cabang = Cabang::all();
        return view('users.edit', compact('user', 'cabang'));
    }

    public function update(Request $request, User $user)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:35',
            'no_hp' => [
                'required',
                'string',
                'max:13',
                Rule::unique('users', 'no_hp')->ignore($user->user_id, 'user_id')
            ],
            'password' => 'nullable|string|min:6',
            'is_active' => 'nullable|boolean',
            'role' => 'required|in:admin,manager,customer_service,koordinator_teknisi,teknisi,asisten_manager,pelanggan',
            'cabang_id' => 'nullable|exists:cabang,cabang_id',
        ]);

        $this->authorizeUserAccess($user);
        // $this->authorizeRoleBranchRules($validated['role'], $validated['cabang_id'] ?? $user->cabang_id);
        $this->ensureSingleBranchRoleAvailability($validated['role'], $validated['cabang_id'] ?? $user->cabang_id, $user);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        DB::transaction(function () use ($user, $validated) {
            $updateData = [
                'name' => $validated['name'],
                'no_hp' => $validated['no_hp'],
                'is_active' => $validated['is_active'] ?? $user->is_active,
                'cabang_id' => $validated['cabang_id'] ?? null,
            ];

            if (! empty($validated['password'])) {
                $updateData['password'] = $validated['password'];
            }

            $user->update([
                ...$updateData,
            ]);

            $user->syncRoles([$validated['role']]);
            $existingPelanggan = $user->profilePelanggan()->first();

            if ($validated['role'] === 'pelanggan') {
                ProfilePelanggan::updateOrCreate(
                    ['user_id' => $user->user_id],
                    [
                        'no_id_pelanggan' => $existingPelanggan?->no_id_pelanggan ?? 'PLG-' . str_pad((string) $user->user_id, 4, '0', STR_PAD_LEFT),
                        'jenis_pelanggan' => $existingPelanggan?->jenis_pelanggan ?? 'R1',
                        'alamat' => $existingPelanggan?->alamat ?? '-',
                        'latitude' => $existingPelanggan?->latitude,
                        'longitude' => $existingPelanggan?->longitude,
                    ]
                );
            } else {
                ProfilePelanggan::where('user_id', $user->user_id)->delete();
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $this->authorizeUserAccess($user);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function toggleActive(User $user)
    {
        $this->authorizeUserAccess($user);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('users.index')
            ->with('success', "User berhasil {$status}");
    }

    private function applyUserScope(Builder $query, User $actor): void
    {
        if ($this->hasGlobalAccess($actor)) {
            return;
        }

        if ($this->hasCabangScopedAccess($actor) && $actor->cabang_id) {
            $query->where('cabang_id', $actor->cabang_id);
        }
    }

    private function authorizeUserAccess(User $target): void
    {
        $actor = auth()->user();

        if ($this->hasGlobalAccess($actor)) {
            return;
        }

        if ($this->hasCabangScopedAccess($actor) && $target->cabang_id === $actor->cabang_id) {
            return;
        }

        abort(403, 'Anda tidak dapat mengelola user di luar cabang Anda.');
    }

    private function authorizeBranchTarget(?int $cabangId): void
    {
        $actor = auth()->user();

        if ($this->hasGlobalAccess($actor)) {
            return;
        }

        if ($this->hasCabangScopedAccess($actor) && $cabangId === $actor->cabang_id) {
            return;
        }

        abort(403, 'Anda tidak dapat memilih cabang di luar cabang Anda.');
    }

    private function authorizeRoleBranchRules(string $role, ?int $cabangId): void
    {
        if ($role === 'manager' && $cabangId !== null) {
            throw ValidationException::withMessages([
                'cabang_id' => 'Manager harus bersifat global dan tidak terikat ke cabang tertentu.',
            ]);
        }

        if (in_array($role, ['customer_service', 'koordinator_teknisi', 'teknisi', 'asisten_manager', 'pelanggan'], true) && $cabangId === null) {
            throw ValidationException::withMessages([
                'cabang_id' => 'Role ini wajib terkait ke salah satu cabang.',
            ]);
        }

        $this->authorizeBranchTarget($cabangId);
    }

    private function ensureSingleBranchRoleAvailability(string $role, ?int $cabangId, ?User $ignoredUser = null): void
    {
        if (! in_array($role, ['customer_service', 'koordinator_teknisi', 'asisten_manager'], true) || $cabangId === null) {
            return;
        }

        $existing = User::role($role)
            ->where('cabang_id', $cabangId)
            ->when($ignoredUser, function ($query) use ($ignoredUser) {
                $query->where('user_id', '!=', $ignoredUser->user_id);
            })
            ->exists();

        if ($existing) {
            throw ValidationException::withMessages([
                'role' => 'Role ini sudah terisi untuk cabang yang dipilih.',
            ]);
        }
    }

    private function getAvailableCabangs()
    {
        $actor = auth()->user();

        return Cabang::query()
            ->when($this->hasCabangScopedAccess($actor) && $actor->cabang_id, function ($query) use ($actor) {
                $query->where('cabang_id', $actor->cabang_id);
            })
            ->orderByRaw("FIELD(nama_cabang, 'Ulu', 'Ilir', 'Demang')")
            ->orderBy('nama_cabang')
            ->get();
    }

    private function hasGlobalAccess(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super-admin', 'manager']);
    }

    private function hasCabangScopedAccess(User $user): bool
    {
        return $user->hasAnyRole(['customer_service', 'koordinator_teknisi', 'asisten_manager']);
    }
}
