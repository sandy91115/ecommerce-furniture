@extends('admin.layouts.app')

@section('title', 'Role Management')

@section('content')
@php
    $systemRoleNames = ['super_admin', 'admin', 'shop_team', 'marketing_team', 'customer', 'vendor'];
@endphp

<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Role & Permission Management</h1>
            
        </div>
        @can('roles.manage')
        <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Create Role
        </a>
        @endcan
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Roles</p>
            <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_roles'] }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">System Roles</p>
            <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['system_roles'] }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Custom Roles</p>
            <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['custom_roles'] }}</p>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Available Permissions</p>
            <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_permissions'] }}</p>
        </div>
    </div>

    <div class="space-y-5">
        @forelse($roles as $role)
        @php
            $isSystemRole = in_array($role->name, $systemRoleNames, true);
            $displayName = \Illuminate\Support\Str::headline(str_replace('_', ' ', $role->name));
            $previewPermissions = $role->permissions->take(6);
            $extraPermissions = max($role->permissions_count - $previewPermissions->count(), 0);
            $hasAdminAccess = $role->permissions->contains('name', 'admin.access') || $role->name === 'super_admin';
        @endphp
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
            <div class="grid gap-6 xl:grid-cols-[280px,minmax(0,1fr),220px]">
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $displayName }}</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Code: {{ $role->name }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $isSystemRole ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200' }}">
                            {{ $isSystemRole ? 'System' : 'Custom' }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $hasAdminAccess ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                            {{ $hasAdminAccess ? 'Admin Access Enabled' : 'No Admin Access' }}
                        </span>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200">
                            {{ $role->users_count }} assigned user{{ $role->users_count === 1 ? '' : 's' }}
                        </span>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Permission Snapshot</h3>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $role->permissions_count }} total</span>
                    </div>

                    @if($role->name === 'super_admin')
                    <div class="mt-4 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-5 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-200">
                        
                        Super Admin has full global access. It didn't need any other permissions.
                    </div>
                    @elseif($previewPermissions->isEmpty())
                    <div class="mt-4 rounded-2xl border border-dashed border-gray-300 px-4 py-8 text-center text-sm text-gray-500 dark:border-gray-600 dark:text-gray-400">
                        No permissions assigned yet.
                    </div>
                    @else
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($previewPermissions as $permission)
                        <span class="inline-flex rounded-full bg-gray-900 px-3 py-1 text-xs font-medium text-white dark:bg-gray-700">
                            {{ $permission->name }}
                        </span>
                        @endforeach
                        @if($extraPermissions > 0)
                        <span class="inline-flex rounded-full bg-gray-200 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                            +{{ $extraPermissions }} more
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="flex flex-col gap-3 xl:items-end">
                    @can('roles.manage')
                    <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700 xl:w-[200px]">
                        <i class="fas fa-sliders-h mr-2"></i>
                        Manage
                    </a>
                    @endcan

                    @can('roles.manage')
                    @if(!$isSystemRole && $role->users_count === 0)
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="w-full xl:w-[200px]">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this role?')" class="inline-flex w-full items-center justify-center rounded-2xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-900/20">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Delete
                        </button>
                    </form>
                    @elseif(!$isSystemRole)
                    <p class="w-full rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-medium text-amber-700 dark:border-amber-800 dark:bg-amber-900/20 dark:text-amber-200 xl:w-[200px]">
                        Reassign users before deleting.
                    </p>
                    @else
                    <p class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-xs font-medium text-gray-600 dark:border-gray-700 dark:bg-gray-900/30 dark:text-gray-300 xl:w-[200px]">
                        Protected system role.
                    </p>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-3xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center dark:border-gray-700 dark:bg-gray-800">
            <i class="fas fa-user-shield text-5xl text-gray-300 dark:text-gray-600"></i>
            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">No roles found</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Create a role to start managing access properly.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
