<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    private const SYSTEM_ROLE_NAMES = [
        'super_admin',
        'admin',
        'shop_team',
        'marketing_team',
        'customer',
        'vendor',
    ];

    public function index()
    {
        $this->authorize('roles.view');

        $roles = Role::with('permissions')
            ->withCount(['permissions', 'users'])
            ->orderByRaw("CASE WHEN name = 'super_admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $stats = [
            'total_roles' => $roles->count(),
            'system_roles' => $roles->whereIn('name', self::SYSTEM_ROLE_NAMES)->count(),
            'custom_roles' => $roles->whereNotIn('name', self::SYSTEM_ROLE_NAMES)->count(),
            'total_permissions' => Permission::count(),
        ];

        return view('admin.roles.index', compact('roles', 'stats'));
    }

    public function create()
    {
        $this->authorize('roles.manage');

        return view('admin.roles.create', $this->buildFormData(new Role([
            'guard_name' => 'web',
        ])));
    }

    public function store(Request $request)
    {
        $this->authorize('roles.manage');

        $data = $this->validateRole($request);

        $role = Role::withTrashed()->firstOrNew([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        if ($role->exists && $role->trashed()) {
            $role->restore();
        }

        $role->guard_name = 'web';
        $role->name = $data['name'];
        $role->save();
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.edit', $role)->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->authorize('roles.manage');

        return view('admin.roles.edit', $this->buildFormData($role));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('roles.manage');

        if ($role->name === 'super_admin') {
            return back()->with('success', 'Super Admin access is managed automatically and remains fully enabled.');
        }

        $data = $this->validateRole($request, $role);

        if (!$this->isSystemRole($role)) {
            $role->update([
                'name' => $data['name'],
            ]);
        }

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.edit', $role->fresh())->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('roles.manage');

        if ($this->isSystemRole($role)) {
            return back()->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'This role is assigned to users. Reassign them before deleting the role.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role moved to Recycle Bin successfully.');
    }

    private function validateRole(Request $request, ?Role $role = null): array
    {
        $rules = [
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ];

        if (!$role || !$this->isSystemRole($role)) {
            $uniqueRule = Rule::unique('roles', 'name')->where(function ($query) {
                $query->whereNull('deleted_at');
            });

            if ($role) {
                $uniqueRule->ignore($role->id);
            }

            $rules['name'] = ['required', 'string', 'max:255', $uniqueRule];
        }

        $data = $request->validate($rules);

        $data['permissions'] = array_values(array_unique($data['permissions'] ?? []));
        $data['name'] = isset($data['name']) ? trim($data['name']) : $role?->name;

        return $data;
    }

    private function buildFormData(Role $role): array
    {
        if ($role->exists) {
            $role->loadMissing('permissions')->loadCount(['permissions', 'users']);
        } else {
            $role->setRelation('permissions', collect());
            $role->setAttribute('permissions_count', 0);
            $role->setAttribute('users_count', 0);
        }

        return [
            'role' => $role,
            'permissionGroups' => Permission::orderBy('name')
                ->get()
                ->groupBy(function (Permission $permission) {
                    return Str::before($permission->name, '.');
                }),
            'selectedPermissions' => $role->permissions->pluck('name')->all(),
            'isSystemRole' => $this->isSystemRole($role),
            'isLockedRole' => $role->name === 'super_admin',
        ];
    }

    private function isSystemRole(Role $role): bool
    {
        return in_array($role->name, self::SYSTEM_ROLE_NAMES, true);
    }
}

