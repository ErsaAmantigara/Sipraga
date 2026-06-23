<?php

namespace App\Http\Middleware;

use App\Models\Cabang;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCabangIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->getRoleNames()[0] !== "manager" && auth()->user()->getRoleNames()[0] !== "super-admin") {
            $id = Auth::id();

            $user = User::where('user_id', $id)->first();
            $cabang = Cabang::where('cabang_id', $user->cabang_id)->first();

            if ($cabang->is_active === false) {
                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->back()
                ->withErrors(['message' => 'Cabang sudah tidak aktif.'])
                ->withInput(); // Keeps form data
            }
        }

        return $next($request);
    }
}
