@extends('admin.layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h4 class="page-title mb-0">Role & Permission Management</h4>
            </div>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive w-100 mb-0">
                        <thead class="table-light sticky-top">
                            <tr class="text-left border-b-2 border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-4 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider w-64 min-w-[16rem] border-r border-gray-200 dark:border-gray-700">Role Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-r border-gray-200 dark:border-gray-700 flex-1 min-w-0">Current Permissions</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider text-center w-96 min-w-[24rem] border-l border-gray-200 dark:border-gray-700">Manage Permissions & Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($roles as $role)
                            <tr class="hover:bg-gray-50/75 dark:hover:bg-gray-900/30 transition-all duration-200 group">
                                <td class="px-6 py-8 align-top border-r border-gray-200/50 dark:border-gray-700/50">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg"></div>
                                        <div>
                                            <span class="text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">{{ ucfirst($role->name) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-8 align-top border-r border-gray-200/50 dark:border-gray-700/50">
                                    <div class="h-48 min-h-[12rem] overflow-y-auto p-5 border border-gray-200/60 dark:border-gray-700/60 rounded-2xl bg-gradient-to-br from-gray-50/60 to-gray-100/60 dark:from-gray-900/50 dark:to-gray-800/50 flex flex-wrap gap-2.5 content-start shadow-xl shadow-gray-100/50 dark:shadow-gray-900/30 scrollbar-thin scrollbar-thumb-gray-400/50 scrollbar-track-gray-200/50 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-800">
                                        @forelse($role->permissions as $perm)
                                        <span class="inline-flex px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800 border border-emerald-200/50 dark:from-emerald-900/60 dark:to-emerald-800/60 dark:text-emerald-200 dark:border-emerald-700/50 rounded-full shadow-sm hover:shadow-md transition-all">{{ $perm->name }}</span>
                                        @empty
                                        <div class="flex flex-col items-center justify-center w-full h-full text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-shield-alt text-4xl opacity-40 mb-3"></i>
                                            <span class="text-sm font-medium italic">No permissions assigned</span>
                                        </div>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-8 align-top border-l border-gray-200/50 dark:border-gray-700/50">
                                    <div class="flex flex-col gap-6">
                                        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="flex flex-col gap-4">
                                            @csrf @method('PUT')
                                            
                                            <div class="flex items-center justify-between gap-4">
                                                <button type="submit" class="flex-1 bg-gradient-to-br from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-xl shadow-blue-500/30 hover:shadow-2xl hover:shadow-blue-500/50 transition-all duration-300 flex items-center justify-center group">
                                                    <i class="fas fa-sync-alt mr-2 group-hover:rotate-180 transition-transform duration-500"></i> Update Permissions
                                                </button>
                                                
                                                @if($role->name !== 'super_admin')
                                                <button type="button" 
                                                        onclick="if(confirm('Are you sure you want to delete this role? This action cannot be undone.')) { document.getElementById('delete-form-{{ $role->id }}').submit(); }"
                                                        class="bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-3 rounded-2xl text-sm font-bold shadow-xl shadow-red-500/30 hover:shadow-2xl hover:shadow-red-500/50 transition-all duration-300 flex items-center justify-center aspect-square"
                                                        title="Delete Role">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                @endif
                                            </div>

                                            <div class="relative group/select">
                                                <select name="permissions[]" multiple class="w-full h-48 border-2 border-gray-200 dark:border-gray-700/50 dark:bg-gray-800/50 dark:text-gray-100 rounded-2xl shadow-inner focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500/50 transition-all duration-300 text-sm p-4 scrollbar-thin scrollbar-thumb-blue-400/50 dark:scrollbar-thumb-blue-600 scrollbar-track-transparent">
                                                    @foreach($permissions as $permission)
                                                    <option value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }} class="py-2 px-3 text-sm hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg m-1 cursor-pointer transition-colors">
                                                        {{ $permission->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-x-0 bottom-0 h-8 bg-gradient-to-t from-gray-50/50 dark:from-gray-800/50 to-transparent pointer-events-none rounded-b-2xl"></div>
                                            </div>
                                        </form>
                                    </div>

                                    @if($role->name !== 'super_admin')
                                    <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-20">
                                    <div class="flex flex-col items-center space-y-4">
                                        <i class="fas fa-users text-6xl text-gray-400 dark:text-gray-500"></i>
                                        <h3 class="text-xl font-bold text-gray-600 dark:text-gray-400">No roles found</h3>
                                        <p class="text-gray-500 dark:text-gray-500">Create your first role to get started.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

