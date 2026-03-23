<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('staff.view');

        $staff = User::with('roles', 'verifier')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'super_admin', 'shop_team', 'marketing_team']);
            })
            ->paginate(10);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $this->authorize('staff.create');
        $roles = Role::whereIn('name', ['shop_team', 'marketing_team'])->get(); // super_admin manual only
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('staff.create');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'designation' => 'nullable|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['verification_status'] = 'verified'; // Staff auto-verified

        $user = User::create($data);
        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully.');
    }

    public function edit(User $staff)
    {
        $this->authorize('staff.update');
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.staff.edit', compact('staff', 'roles', 'permissions'));
    }

    public function update(Request $request, User $staff)
    {
        $this->authorize('staff.update');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'array',
        ]);

        if ($request->password) {
            $data['password'] = Hash::make($data['password']);
        }
        unset($data['password_confirmation']);

        $staff->update($data);

        if ($request->roles) {
            $staff->syncRoles($request->roles);
        }

        if ($request->permissions) {
            $staff->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(User $staff)
    {
        $this->authorize('staff.delete');
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff moved to Recycle Bin successfully.');
    }
}

