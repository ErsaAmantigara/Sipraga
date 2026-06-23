<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function checkPermission(string $permission): void
    {
        if (!auth()->check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return;
        }

        if (!$user->can($permission) && !$user->hasPermissionTo($permission)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    protected function getPermissionFromRoute(string $routeName): ?string
    {
        $resourceActions = [
            'index' => 'view',
            'create' => 'create',
            'store' => 'create',
            'show' => 'view',
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
        ];

        $moduleMappings = [
            'users' => 'users',
            'roles' => 'roles',
            'pelanggan' => 'pelanggan',
            'cabang' => 'cabang',
            'kriteria-saw' => 'kriteria',
            'pengaduan' => 'pengaduan',
            'pengerjaan' => 'pengerjaan',
            'notifikasi' => 'notifikasi',
            'laporan' => 'laporan',
            'dashboard' => 'dashboard',
            'nilai-pengaduan-saw' => 'saw',
            'hasil-saw' => 'saw',
        ];

        foreach ($moduleMappings as $prefix => $module) {
            if (str_starts_with($routeName, $prefix)) {
                $action = str_replace($prefix . '.', '', $routeName);
                
                if (isset($resourceActions[$action])) {
                    return $module . '.' . $resourceActions[$action];
                }
                
                return $module . '.view';
            }
        }

        return null;
    }

    protected function authorizeByRoute(): void
    {
        if (!auth()->check()) {
            return;
        }

        $routeName = request()->route()?->getName();

        if (!$routeName) {
            return;
        }

        $user = auth()->user();

        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return;
        }

        $permission = $this->getPermissionFromRoute($routeName);

        if ($permission && !$user->can($permission) && !$user->hasPermissionTo($permission)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
