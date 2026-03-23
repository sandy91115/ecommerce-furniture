<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('roles.view');
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('roles.manage');

        $data = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', 'Role permissions updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('roles.manage');
        if ($role->name === 'super_admin') {
            return back()->with('error', 'Cannot delete super_admin role.');
        }
        $role->delete();
        return back()->with('success', 'Role moved to Recycle Bin successfully.');
    }
}

