@extends('admin.layouts.app')

@section('title', 'Create Staff')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Staff</h1>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">Add a new team member to your staff directory.</p>
        </div>
        <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center px-6 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-medium shadow-sm transition-all">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Staff
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-8 lg:p-12 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Avatar -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-user-circle text-2xl mr-3 text-blue-500"></i>
                            Profile Photo
                        </label>
                        <div class="relative">
                            <div id="avatarPreview" class="w-32 h-32 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-400 border-4 border-dashed border-gray-300 dark:border-gray-600 mx-auto mb-4 shadow-lg group hover:border-blue-400 transition-all">
                                <i class="fas fa-camera text-3xl opacity-50 group-hover:opacity-75"></i>
                            </div>
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer rounded-full">
                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-2">PNG, JPG up to 2MB. Click photo to upload.</p>
                        </div>
                        @error('avatar') <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-white mb-3">
                            <i class="fas fa-user text-blue-500 mr-3"></i>
                            Full Name *
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-all @error('name') border-red-500 ring-red-500/30 @enderror">
                        @error('name') <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-white mb-3">
                            <i class="fas fa-envelope text-green-500 mr-3"></i>
                            Email Address *
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-all @error('email') border-red-500 ring-red-500/30 @enderror">
                        @error('email') <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-white mb-3">
                            <i class="fas fa-phone text-purple-500 mr-3"></i>
                            Phone Number
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" 
                               class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-all @error('phone') border-red-500 ring-red-500/30 @enderror" placeholder="+1 (555) 123-4567">
                        @error('phone') <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Password -->
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-white mb-3">
                            <i class="fas fa-lock text-orange-500 mr-3"></i>
                            Password *
                        </label>
                        <div class="space-y-3">
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-all @error('password') border-red-500 ring-red-500/30 @enderror" placeholder="At least 8 characters">
                            <input type="password" name="password_confirmation" required 
                                   class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-all" placeholder="Confirm password">
                        </div>
                        @error('password') <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <!-- Designation -->
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-white mb-3">
                            <i class="fas fa-briefcase text-indigo-500 mr-3"></i>
                            Designation
                        </label>
                        <input type="text" name="designation" value="{{ old('designation') }}" 
                               class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-3 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-all @error('designation') border-red-500 ring-red-500/30 @enderror" placeholder="e.g., Marketing Manager">
                        @error('designation') <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>

                    <!-- Roles -->
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-white mb-4">
                            <i class="fas fa-users-cog text-teal-500 mr-3"></i>
                            Roles *
                        </label>
                        <div class="grid grid-cols-2 gap-3 max-h-48 overflow-y-auto p-3 bg-gray-50 dark:bg-gray-700 rounded-xl border-2 border-dashed border-dashed-gray-200 dark:border-gray-600">
                            @foreach($roles as $role)
                                <label class="flex items-center p-3 bg-white dark:bg-gray-600 rounded-lg shadow-sm hover:shadow-md transition-all cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }} 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white block flex-1 truncate">{{ ucfirst($role->name) }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Role
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles') <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-5 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-3"></i>
                    Create Staff Member
                </button>
                <a href="{{ route('admin.staff.index') }}" class="flex-1 inline-flex items-center justify-center px-8 py-5 border border-gray-300 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 font-medium shadow-sm transition-all">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const preview = document.getElementById('avatarPreview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full shadow-lg" alt="Preview">`;
            preview.classList.remove('bg-gradient-to-br', 'text-gray-500', 'dark:text-gray-400', 'border-dashed');
            preview.classList.add('border-blue-400', 'shadow-xl');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection

