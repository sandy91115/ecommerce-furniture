<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('staff.view');

        $staff = User::with('roles', 'verifier')
            ->whereHas('roles.permissions', function ($query) {
                $query->where('name', 'admin.access');
            })
            ->paginate(10);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $this->authorize('staff.create');
        $roles = $this->adminAssignableRoles();
        $permissions = collect();
        $staff = null;
        return view('admin.staff.create', compact('roles', 'permissions', 'staff'));
    }

    public function store(Request $request)
    {
        $this->authorize('staff.create');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|min:8|confirmed',
            'designation' => 'nullable|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $avatarPath;
        }

        $data['password'] = Hash::make($data['password']);
        $data['verification_status'] = 'verified'; // Staff auto-verified

        $user = User::create($data);
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully.');
    }

    public function edit(User $staff)
    {
        $this->authorize('staff.update');
        $roles = $this->adminAssignableRoles();
        $permissions = Permission::orderBy('name')->get();
        return view('admin.staff.edit', compact('staff', 'roles', 'permissions'));
    }

    public function update(Request $request, User $staff)
    {
        $this->authorize('staff.update');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:8|confirmed',
            'designation' => 'nullable|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'array',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($staff->avatar_path && Storage::disk('public')->exists($staff->avatar_path)) {
                Storage::disk('public')->delete($staff->avatar_path);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $avatarPath;
        }

        if ($request->password) {
            $data['password'] = Hash::make($data['password']);
        }
        unset($data['password_confirmation']);

        $staff->update($data);

        $staff->syncRoles($request->input('roles', []));
        $staff->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(User $staff)
    {
        $this->authorize('staff.delete');
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff moved to Recycle Bin successfully.');
    }

    private function adminAssignableRoles()
    {
        return Role::where('name', '!=', 'super_admin')
            ->whereHas('permissions', function ($query) {
                $query->where('name', 'admin.access');
            })
            ->orderBy('name')
            ->get();
    }
}

