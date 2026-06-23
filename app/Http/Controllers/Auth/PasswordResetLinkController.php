<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        private readonly FonnteService $fonnteService,
    ) {
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'exists:users,no_hp'],
        ]);

        $user = User::where('no_hp', $validated['no_hp'])->firstOrFail();
        $otp = $this->generateOtp();

        DB::table('password_reset_tokens')->updateOrInsert(
            ['no_hp' => $user->no_hp],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        try {
            $this->fonnteService->sendOtp($user->no_hp, $otp);
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput($request->only('no_hp'))
                ->withErrors(['no_hp' => 'OTP gagal dikirim ke WhatsApp. Periksa token atau koneksi Fonnte.']);
        }

        return redirect()->route('password.reset', ['token' => Str::random(40), 'no_hp' => $user->no_hp])
            ->with('success', 'OTP berhasil dikirim ke WhatsApp Anda.');
    }

    private function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
