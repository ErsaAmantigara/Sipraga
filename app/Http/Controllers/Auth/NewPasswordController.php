<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'exists:users,no_hp'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $resetToken = DB::table('password_reset_tokens')
            ->where('no_hp', $validated['no_hp'])
            ->first();

        if (! $resetToken) {
            return back()
                ->withInput($request->only('no_hp'))
                ->withErrors(['otp' => 'OTP tidak ditemukan. Silakan minta OTP baru.']);
        }

        if (now()->diffInMinutes($resetToken->created_at) > 10) {
            DB::table('password_reset_tokens')->where('no_hp', $validated['no_hp'])->delete();

            return back()
                ->withInput($request->only('no_hp'))
                ->withErrors(['otp' => 'OTP sudah kedaluwarsa. Silakan minta OTP baru.']);
        }

        if (! Hash::check($validated['otp'], $resetToken->token)) {
            return back()
                ->withInput($request->only('no_hp'))
                ->withErrors(['otp' => 'OTP yang Anda masukkan tidak valid.']);
        }

        $user = User::where('no_hp', $validated['no_hp'])->firstOrFail();
        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        DB::table('password_reset_tokens')->where('no_hp', $validated['no_hp'])->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }
}
