<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\ProfilePelanggan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $cabangs = Cabang::query()
            ->where('is_active', true)
            ->whereIn('nama_cabang', ['Ulu', 'Ilir', 'Demang'])
            ->orderByRaw("FIELD(nama_cabang, 'Ulu', 'Ilir', 'Demang')")
            ->get();

        return view('auth.register', compact('cabangs'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:35'],
            'no_hp' => ['required', 'string', 'max:13', 'unique:' . User::class],
            'no_id_pelanggan' => ['required', 'string', 'max:20', 'unique:profile_pelanggan,no_id_pelanggan'],
            'jenis_pelanggan' => ['required', 'in:R1,R2,PK1,PK2'],
            'cabang_id' => ['required', 'exists:cabang,cabang_id'],
            'alamat' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                'is_active' => true,
                'cabang_id' => $request->cabang_id,
            ]);

            $user->assignRole('pelanggan');

            ProfilePelanggan::create([
                'user_id' => $user->user_id,
                'no_id_pelanggan' => $request->no_id_pelanggan,
                'jenis_pelanggan' => $request->jenis_pelanggan,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return $user;
        });

        return redirect(route('login'));
    }
}
