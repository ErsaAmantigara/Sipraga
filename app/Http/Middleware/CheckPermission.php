<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if (!$user->can($permission)) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}