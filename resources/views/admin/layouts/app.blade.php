<!DOCTYPE html>
<html lang="en" class="{{ \App\Models\Setting::get('admin_theme_mode', 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="admin-settings-update-url" content="{{ route('admin.settings.update') }}">
    <title>@yield('title', \App\Models\Setting::get('site_name', 'Furnixar') . ' Admin')</title>
    @php
        $adminFaviconPath = \App\Models\Setting::get('admin_favicon_path');
        $adminFaviconUrl = $adminFaviconPath ? asset('storage/' . $adminFaviconPath) : asset('assets/img/favicon.png');
    @endphp
    <link rel="icon" href="{{ $adminFaviconUrl }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
<<<<<<< HEAD
    <link rel="stylesheet" href="{{ versioned_asset('assets/css/admin-dashboard.css') }}">
=======
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>
    <style>
        .sidebar { transition: all 0.3s; }
        .sidebar.collapsed { width: 64px; }
        
:root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --border-color: #e2e8f0;
            --input-bg: #ffffff;
            --focus-ring: 0 0 0 3px rgba(59,130,246,0.5);
        }
        .dark {
            --bg-primary: #0f0f23;
            --bg-secondary: #1e1b31;
            --bg-tertiary: #27253a;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --border-color: #334155;
            --input-bg: #1e293b;
            --focus-ring: 0 0 0 3px rgba(99,102,241,0.5);
        }
        body { background-color: var(--bg-primary); color: var(--text-primary); }
        .admin-shell { align-items: flex-start; }
        .admin-sidebar {
            background-color: var(--bg-secondary);
            position: sticky;
            top: 0;
            align-self: flex-start;
            height: 100vh;
            overflow-y: auto;
            z-index: 40;
        }
        .admin-sidebar a:hover { background-color: var(--border-color); }
        .admin-content {
            min-width: 0;
            min-height: 100vh;
        }
        .admin-topbar {
            background-color: var(--bg-secondary);
            border-bottom-color: var(--border-color);
            position: sticky;
            top: 0;
            z-index: 30;
        }

        /* Tailwind utility overrides for dark mode */
        .dark .bg-white { background-color: var(--bg-secondary) !important; }
        .dark .bg-gray-50 { background-color: var(--bg-primary) !important; }
        .dark .bg-gray-100 { background-color: var(--bg-tertiary) !important; }
        .dark .hover\:bg-gray-50:hover { background-color: var(--bg-tertiary) !important; }
        .dark .hover\:bg-gray-100:hover { background-color: var(--bg-tertiary) !important; }
        .dark .admin-sidebar a.bg-blue-50 { background-color: var(--bg-tertiary) !important; }

        /* Enhanced Tailwind overrides for complete dark mode coverage */
        .dark .text-black, 
        .dark .text-gray-900, 
        .dark .text-gray-800, 
        .dark .text-gray-700 { 
            color: var(--text-primary) !important; 
        }

        .dark .text-gray-600, 
        .dark .text-gray-500 { 
            color: var(--text-secondary) !important; 
        }

        .dark .text-gray-400 { 
            color: #9ca3af !important; 
        }

        .dark .border-gray-100, 
        .dark .border-gray-200, 
        .dark .border-gray-300, 
        .dark .border-gray-400 { 
            border-color: var(--border-color) !important; 
        }

        .dark .border, .dark .border-b, .dark .border-t, .dark .border-l, .dark .border-r {
            border-color: var(--border-color) !important;
        }

        /* Form elements */
        .dark label {
            color: var(--text-primary) !important;
        }
        .dark input,
        .dark textarea,
        .dark select {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        .dark input::placeholder,
        .dark textarea::placeholder { 
            color: var(--text-secondary) !important; 
        }

        /* Buttons & links */
        .dark button:not(.btn-primary):not(.btn-success):not(.btn-danger),
        .dark a:not(.text-blue-600) {
            color: var(--text-primary);
        }

        /* CKEditor dark mode (Blog editor) */
        .dark .ck.ck-editor {
            --ck-color-base-background: var(--bg-secondary);
            --ck-color-base-foreground: var(--bg-tertiary);
            --ck-color-base-border: var(--border-color);
            --ck-color-base-action: #3b82f6;
            --ck-color-base-focus: #3b82f6;
            --ck-color-panel-background: var(--bg-secondary);
            --ck-color-panel-border: var(--border-color);
            --ck-color-text: var(--text-primary);
            --ck-color-shadow-drop: rgba(0, 0, 0, 0.4);
            --ck-color-shadow-inner: rgba(0, 0, 0, 0.2);
            --ck-color-button-default-hover-background: var(--bg-tertiary);
            --ck-color-button-default-active-background: var(--bg-tertiary);
            --ck-color-button-on-background: var(--bg-tertiary);
            --ck-color-button-on-hover-background: var(--bg-tertiary);
            --ck-color-button-on-active-background: var(--bg-tertiary);
            --ck-color-dropdown-panel-background: var(--bg-secondary);
            --ck-color-dropdown-panel-border: var(--border-color);
            --ck-color-input-background: var(--bg-primary);
            --ck-color-input-border: var(--border-color);
            --ck-color-input-text: var(--text-primary);
            --ck-color-tooltip-background: var(--bg-secondary);
            --ck-color-tooltip-text: var(--text-primary);
            --ck-focus-ring: var(--focus-ring);
        }
        .dark .ck.ck-toolbar {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
        }
        .dark .ck.ck-editor__main > .ck-editor__editable {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        .dark .ck.ck-editor__main > .ck-editor__editable.ck-focused {
            border-color: #3b82f6 !important;
            box-shadow: var(--focus-ring) !important;
        }
        .dark .ck.ck-editor__editable.ck-placeholder::before {
            color: var(--text-secondary) !important;
        }
        .dark .ck .ck-button,
        .dark .ck .ck-button__label {
            color: var(--text-primary) !important;
        }
        .dark .ck .ck-icon {
            fill: var(--text-primary) !important;
        }
        .dark .ck.ck-balloon-panel,
        .dark .ck.ck-dropdown__panel {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
        }
        .dark .ck.ck-powered-by a {
            color: var(--text-secondary) !important;
        }

        /* Tables & lists */
        .dark table th, .dark table td {
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        /* Enhanced Dark Mode Visibility */
        .dark .admin-sidebar { 
            background-color: var(--bg-secondary) !important; 
        }
        .dark .admin-sidebar a { 
            color: var(--text-primary) !important; 
        }
        .dark .admin-sidebar a:hover { 
            background-color: var(--bg-tertiary) !important; 
        }

        /* Form Enhancements */
        .dark input:focus, .dark textarea:focus, .dark select:focus {
            background-color: var(--input-bg) !important;
            box-shadow: var(--focus-ring) !important;
            border-color: #3b82f6 !important;
        }

        /* Badge Overrides */
        .dark .bg-green-100 { background-color: #166534 !important; color: #dcfce7 !important; }
        .dark .bg-blue-100 { background-color: #1e40af !important; color: #dbeafe !important; }
        .dark .bg-red-100 { background-color: #dc2626 !important; color: #fef2f2 !important; }
        .dark .bg-yellow-100 { background-color: #d97706 !important; color: #fefce8 !important; }
        .dark .bg-purple-100 { background-color: #7c3aed !important; color: #ede9fe !important; }

        /* Shadows for depth */
        .dark .shadow-lg { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.4), 0 10px 10px -5px rgba(0,0,0,0.2) !important; }

        /* Topbar header */
        .dark .admin-topbar { background-color: var(--bg-secondary) !important; }
    </style>
</head>
<body class="admin-shell min-h-screen flex">
    <!-- Sidebar -->
    <div class="admin-sidebar sidebar w-64 bg-white shadow-lg flex-shrink-0 min-h-screen h-screen flex flex-col">
        <div class="p-4 border-b">
            @php
                $adminLogoPath = \App\Models\Setting::get('admin_logo_path');
                $adminLogoUrl = $adminLogoPath ? asset('storage/' . $adminLogoPath) : asset('assets/img/favicon.png');
            @endphp
            <img src="{{ $adminLogoUrl }}" alt="Logo" class="h-10 w-auto mb-1">
            <p class="text-sm text-gray-600">Admin Panel</p>
        </div>
        @php
<<<<<<< HEAD
            $adminPathPrefix = trim(env('ADMIN_PATH', 'panel'), '/') ?: 'panel';
            $adminUrl = function ($url) use ($adminPathPrefix) {
                $url = (string) $url;

                if ($url === '/admin') {
                    return url('/' . $adminPathPrefix);
                }

                if (\Illuminate\Support\Str::startsWith($url, '/admin/')) {
                    return url('/' . $adminPathPrefix . substr($url, strlen('/admin')));
                }

                return $url;
            };
            $adminSidebarMenus = collect($menus['admin_sidebar'] ?? []);
            $normalizedAdminMenuPath = function ($url) use ($adminPathPrefix) {
                $path = parse_url((string) $url, PHP_URL_PATH) ?: (string) $url;
                $path = '/' . trim($path, '/');
                $path = str_replace('/' . $adminPathPrefix . '/', '/admin/', $path);

                return rtrim($path, '/') ?: '/';
            };
            if (! $adminSidebarMenus->contains(fn ($menu) => $normalizedAdminMenuPath($menu->url) === '/admin/seo')) {
                $adminSidebarMenus->push((object) [
                    'url' => '/admin/seo',
                    'title' => 'SEO',
                    'icon' => 'fas fa-magnifying-glass-chart',
                    'permission' => 'admin.access',
                    'children' => collect(),
                ]);
            }
            $recycleBinMenu = $adminSidebarMenus->first(fn ($menu) => $normalizedAdminMenuPath($menu->url) === '/admin/trash');
            $primarySidebarMenus = $adminSidebarMenus->reject(fn ($menu) => $normalizedAdminMenuPath($menu->url) === '/admin/trash');
=======
            $adminSidebarMenus = collect($menus['admin_sidebar'] ?? []);
            $recycleBinMenu = $adminSidebarMenus->first(fn ($menu) => $menu->url === '/admin/trash');
            $primarySidebarMenus = $adminSidebarMenus->reject(fn ($menu) => $menu->url === '/admin/trash');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            $recycleBinMenu = $recycleBinMenu ?: (object) [
                'url' => route('admin.trash.index'),
                'title' => 'Recycle Bin',
                'icon' => 'fas fa-recycle',
                'permission' => 'admin.access',
            ];
        @endphp
        <nav class="mt-8 flex-1 pb-6">
            @foreach($primarySidebarMenus as $menu)
                @php
<<<<<<< HEAD
                    $menuUrl = $adminUrl($menu->url);
                    $menuPath = ltrim(parse_url($menuUrl, PHP_URL_PATH) ?? $menuUrl, '/');
                    $menuIsActive = request()->is($menuPath) || request()->is($menuPath . '/*');
                @endphp
                @can($menu->permission ?? 'admin.access')
                <a href="{{ $menuUrl }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ $menuIsActive ? 'bg-blue-50 border-r-4 border-blue-500 text-blue-600' : '' }}">
=======
                    $menuPath = ltrim(parse_url($menu->url, PHP_URL_PATH) ?? $menu->url, '/');
                    $menuIsActive = request()->is($menuPath) || request()->is($menuPath . '/*');
                @endphp
                @can($menu->permission ?? 'admin.access')
                <a href="{{ $menu->url }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ $menuIsActive ? 'bg-blue-50 border-r-4 border-blue-500 text-blue-600' : '' }}">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                    <i class="w-6 text-lg fa {{ $menu->icon ?? 'fa-circle' }} mr-4"></i>
                    <span>{{ $menu->title }}</span>
                </a>
                @endcan
                @if($menu->children && $menu->children->count() > 0)
                    @foreach($menu->children as $child)
                        @php
<<<<<<< HEAD
                            $childUrl = $adminUrl($child->url);
                            $childPath = ltrim(parse_url($childUrl, PHP_URL_PATH) ?? $childUrl, '/');
                            $childIsActive = request()->is($childPath) || request()->is($childPath . '/*');
                        @endphp
                        @can($child->permission ?? 'admin.access')
                        <a href="{{ $childUrl }}" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 {{ $childIsActive ? 'bg-blue-50 border-r-4 border-blue-300 text-blue-500' : '' }}">
=======
                            $childPath = ltrim(parse_url($child->url, PHP_URL_PATH) ?? $child->url, '/');
                            $childIsActive = request()->is($childPath) || request()->is($childPath . '/*');
                        @endphp
                        @can($child->permission ?? 'admin.access')
                        <a href="{{ $child->url }}" class="flex items-center px-8 py-3 text-gray-600 hover:bg-gray-100 pl-12 {{ $childIsActive ? 'bg-blue-50 border-r-4 border-blue-300 text-blue-500' : '' }}">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                            <i class="w-4 text-lg fa {{ $child->icon ?? 'fa-angle-right' }} mr-3 flex-shrink-0"></i>
                            <span>{{ $child->title }}</span>
                        </a>
                        @endcan
                    @endforeach
                @endif
            @endforeach
        </nav>
        @php
<<<<<<< HEAD
            $binUrl = $adminUrl($recycleBinMenu->url);
            $binPath = ltrim(parse_url($binUrl, PHP_URL_PATH) ?? $binUrl, '/');
=======
            $binPath = ltrim(parse_url($recycleBinMenu->url, PHP_URL_PATH) ?? $recycleBinMenu->url, '/');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            $binIsActive = request()->is($binPath) || request()->is($binPath . '/*');
        @endphp
        @can($recycleBinMenu->permission ?? 'admin.access')
        <div class="mt-auto border-t p-4">
<<<<<<< HEAD
            <a href="{{ $binUrl }}" class="flex items-center rounded-lg px-4 py-3 text-gray-700 hover:bg-gray-100 {{ $binIsActive ? 'bg-blue-50 text-blue-600' : '' }}">
=======
            <a href="{{ $recycleBinMenu->url }}" class="flex items-center rounded-lg px-4 py-3 text-gray-700 hover:bg-gray-100 {{ $binIsActive ? 'bg-blue-50 text-blue-600' : '' }}">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                <i class="w-6 text-lg fa {{ $recycleBinMenu->icon ?? 'fa-recycle' }} mr-4"></i>
                <span>{{ $recycleBinMenu->title }}</span>
            </a>
        </div>
        @endcan
    </div>

    <!-- Main Content -->
    <div class="admin-content flex-1 flex flex-col">
        <!-- Topbar -->
        <header class="admin-topbar bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
<div class="flex items-center">
                        
                        <button class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <div class="flex items-center space-x-4">
                       
                        <button data-theme-toggle class="p-2 rounded-lg hover:bg-gray-100 transition" title="Toggle Dark Mode">
                            <i class="fas fa-moon text-xl text-gray-500 hover:text-gray-700 dark:text-gray-400"></i>
                        </button>
                        <div class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-2 rounded relative group">
                            <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=Admin&background=4f46e5&color=fff" alt="Admin">
<<<<<<< HEAD
                            <span class="font-medium text-gray-700">{{ auth()->user()?->name ?? 'Admin' }}</span>
=======
                            <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                            <i class="fas fa-chevron-down text-sm text-gray-500"></i>
                            
                            <!-- Dropdown -->
                            <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 min-w-0 p-6">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
<<<<<<< HEAD
            @if (isset($errors) && $errors->any())
=======
            @if ($errors->any())
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
@yield('content')

        </main>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <script>
        const descriptionField = document.querySelector('#description');

        if (descriptionField) {
            ClassicEditor
                .create(descriptionField, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
                })
                .then(editor => {
                    const form = descriptionField.closest('form');

                    if (form) {
                        form.addEventListener('submit', () => {
                            descriptionField.value = editor.getData();
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<<<<<<< HEAD
    <script src="{{ versioned_asset('assets/js/admin-settings.js') }}"></script>
=======
    <script src="{{ asset('assets/js/admin-settings.js') }}"></script>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    @stack('scripts')

</body>
</html>
