<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $roles = Role::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'asc')
        ->paginate(10);

        return view('roles.index', compact('roles', 'search'));
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });

        $role->load('permissions');

        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'nullable|string|max:50',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'web',
        ]);

        if (isset($validated['permissions'])) {
            $permissionNames = Permission::whereIn('id', $validated['permissions'])->pluck('name')->toArray();
            $role->syncPermissions($permissionNames);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Role super-admin tidak dapat dihapus');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dihapus');
    }
}