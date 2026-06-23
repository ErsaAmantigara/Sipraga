<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $permissions = Permission::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(20);

        $groupedPermissions = $permissions->getCollection()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });

        return view('permissions.index', compact('permissions', 'search', 'groupedPermissions'));
    }
}