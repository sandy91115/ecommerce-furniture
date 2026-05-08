@extends('admin.layouts.app')

@section('title', 'Menu Manager')

@section('content')
    @php
        $allMenus = collect($menuGroups)->flatMap(fn ($typeMenus) => $typeMenus)->values();
    @endphp

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Menu Manager</h1>
            <p class="text-gray-600 mt-1">Manage header, footer, and sidebar menus</p>
        </div>
<<<<<<< HEAD
    <select id="menuTypeFilter" onchange="window.location.href = '{{ route('admin.menus.index') }}?type=' + this.value" class="p-2 border border-gray-300 rounded-lg">
=======
    <select id="menuTypeFilter" onchange="window.location.href = '/admin/menus?type=' + this.value" class="p-2 border border-gray-300 rounded-lg">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <option value="all">All Menus</option>
            @foreach($menuTypes as $type)
                <option value="{{ $type }}" {{ $type == request('type') ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>
    </div>



    <!-- Add New Menu Modal Trigger -->
    <button type="button" onclick="openModal('addModal')" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 mb-6">
        <i class="fas fa-plus mr-2"></i>Add New Menu Item
    </button>

    <!-- Menus Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($menuGroups as $type => $typeMenus)
                    @foreach($typeMenus as $menu)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
<<<<<<< HEAD
                                <div class="font-medium text-gray-900 flex items-center gap-2">
                                    <span>{{ $menu->title }}</span>
                                   
                                </div>
=======
                                <div class="font-medium text-gray-900">{{ $menu->title }}</div>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                                @if($menu->children->count() > 0)
                                    <div class="text-sm text-gray-500">Has {{ $menu->children->count() }} sub-items</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ $menu->url }}"
                                    class="text-blue-600 hover:text-blue-900 text-sm">{{ $menu->url ?: '#' }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $menu->order }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full {{ $menu->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($menu->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button
                                    type="button"
                                    onclick="openEditModal(this)"
                                    data-update-url="{{ route('admin.menus.update', $menu) }}"
                                    data-menu-id="{{ $menu->id }}"
                                    data-menu-type="{{ $menu->menu_type }}"
                                    data-title="{{ $menu->title }}"
                                    data-url="{{ $menu->url }}"
                                    data-parent-id="{{ $menu->parent_id }}"
                                    data-order="{{ $menu->order }}"
                                    data-status="{{ $menu->status }}"
                                    class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
<<<<<<< HEAD
                                @if($menu->is_permanent)
                                    <span class="text-gray-400" title="Permanent menu items cannot be deleted">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" class="inline"
                                        onsubmit="return confirm('Delete this menu?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
=======
                                <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" class="inline"
                                    onsubmit="return confirm('Delete this menu?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4"
        onclick="closeModal('addModal')">
        <div class="bg-white rounded-lg p-8 max-w-md w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <h2 class="text-2xl font-bold mb-6">Add New Menu Item</h2>
            <form method="POST" action="{{ route('admin.menus.store') }}">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Type</label>
                    <select name="menu_type" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @foreach($menuTypes as $type)
                            <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
<<<<<<< HEAD
                    <label class="block text-sm font-medium text-gray-700 mb-2">CMS Page (Optional)</label>
                    <select name="cms_page_id" data-page-select data-title-field="input[name='title']" data-url-field="input[name='url']"
                        class="w-full p-3 border border-gray-300 rounded-lg">
                        <option value="">Custom URL</option>
                        @foreach($cmsPages as $cmsPage)
                            <option value="{{ $cmsPage->id }}" data-title="{{ $cmsPage->title }}" data-url="/{{ $cmsPage->slug }}">
                                {{ $cmsPage->title }} (/{{ $cmsPage->slug }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                    <input type="text" name="url" placeholder="/about or https://example.com/about"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Menu (Optional)</label>
                    <select name="parent_id" class="w-full p-3 border border-gray-300 rounded-lg">
                        <option value="">None (Top Level)</option>
                        @foreach($allMenus as $parentMenu)
<<<<<<< HEAD
                            <option value="{{ $parentMenu->id }}" data-menu-type="{{ $parentMenu->menu_type }}">— {{ $parentMenu->title }}</option>
=======
                            <option value="{{ $parentMenu->id }}">— {{ $parentMenu->title }}</option>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input type="number" name="order" value="0" min="0"
                        class="w-full p-3 border border-gray-300 rounded-lg">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" required class="w-full p-3 border border-gray-300 rounded-lg">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                        Save
                    </button>
                    <button type="button" onclick="closeModal('addModal')"
                        class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4"
        onclick="closeModal('editModal')">
        <div class="bg-white rounded-lg p-8 max-w-md w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <h2 class="text-2xl font-bold mb-6">Edit Menu Item</h2>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="editMenuId" name="menu_id" value="">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Type</label>
                    <select id="edit_menu_type" name="menu_type" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @foreach($menuTypes as $type)
                            <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input id="edit_title" type="text" name="title" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
<<<<<<< HEAD
                    <label class="block text-sm font-medium text-gray-700 mb-2">CMS Page (Optional)</label>
                    <select id="edit_cms_page_id" name="cms_page_id" data-page-select data-title-field="#edit_title" data-url-field="#edit_url"
                        class="w-full p-3 border border-gray-300 rounded-lg">
                        <option value="">Custom URL</option>
                        @foreach($cmsPages as $cmsPage)
                            <option value="{{ $cmsPage->id }}" data-title="{{ $cmsPage->title }}" data-url="/{{ $cmsPage->slug }}">
                                {{ $cmsPage->title }} (/{{ $cmsPage->slug }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                    <input id="edit_url" type="text" name="url" placeholder="/about or https://example.com/about"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Menu (Optional)</label>
                    <select id="edit_parent_id" name="parent_id" class="w-full p-3 border border-gray-300 rounded-lg">
                        <option value="">None (Top Level)</option>
                        @foreach($allMenus as $parentMenu)
<<<<<<< HEAD
                            <option value="{{ $parentMenu->id }}" data-menu-type="{{ $parentMenu->menu_type }}">— {{ $parentMenu->title }}</option>
=======
                            <option value="{{ $parentMenu->id }}">— {{ $parentMenu->title }}</option>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                    <input id="edit_order" type="number" name="order" value="0" min="0"
                        class="w-full p-3 border border-gray-300 rounded-lg">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="edit_status" name="status" required class="w-full p-3 border border-gray-300 rounded-lg">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                        Update
                    </button>
                    <button type="button" onclick="closeModal('editModal')"
                        class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openModal(modalId) {
                const modal = document.getElementById(modalId);

                if (!modal) {
                    return;
                }

                modal.classList.remove('hidden');
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);

                if (!modal) {
                    return;
                }

                modal.classList.add('hidden');
            }

            function openEditModal(button) {
                const updateUrl = button.dataset.updateUrl;

                if (!updateUrl) {
                    console.error('Missing menu data for edit modal.');
                    return;
                }

                const editForm = document.getElementById('editForm');
                const parentSelect = document.getElementById('edit_parent_id');

                if (!editForm || !parentSelect) {
                    return;
                }

                editForm.action = updateUrl;
                document.getElementById('editMenuId').value = button.dataset.menuId ?? '';
                document.getElementById('edit_menu_type').value = button.dataset.menuType ?? '';
                document.getElementById('edit_title').value = button.dataset.title ?? '';
                document.getElementById('edit_url').value = button.dataset.url ?? '';
<<<<<<< HEAD
                document.getElementById('edit_cms_page_id').value = '';
                document.getElementById('edit_order').value = button.dataset.order ?? 0;
                document.getElementById('edit_status').value = button.dataset.status ?? 'active';

                filterParentOptions(document.getElementById('edit_menu_type'), parentSelect);

=======
                document.getElementById('edit_order').value = button.dataset.order ?? 0;
                document.getElementById('edit_status').value = button.dataset.status ?? 'active';

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                Array.from(parentSelect.options).forEach((option) => {
                    option.disabled = option.value !== '' && Number(option.value) === Number(button.dataset.menuId);
                });

                parentSelect.value = button.dataset.parentId ? String(button.dataset.parentId) : '';

                openModal('editModal');
            }

            window.openModal = openModal;
<<<<<<< HEAD
                window.closeModal = closeModal;
                window.openEditModal = openEditModal;

                function filterParentOptions(typeSelect, parentSelect) {
                    if (!typeSelect || !parentSelect) {
                        return;
                    }

                    const selectedType = typeSelect.value;

                    Array.from(parentSelect.options).forEach((option) => {
                        option.hidden = option.value !== '' && option.dataset.menuType !== selectedType;

                        if (option.hidden && option.selected) {
                            parentSelect.value = '';
                        }
                    });
                }

                const addTypeSelect = document.querySelector('#addModal select[name="menu_type"]');
                const addParentSelect = document.querySelector('#addModal select[name="parent_id"]');
                const editTypeSelect = document.getElementById('edit_menu_type');
                const editParentSelect = document.getElementById('edit_parent_id');

                filterParentOptions(addTypeSelect, addParentSelect);
                addTypeSelect?.addEventListener('change', () => filterParentOptions(addTypeSelect, addParentSelect));
                editTypeSelect?.addEventListener('change', () => filterParentOptions(editTypeSelect, editParentSelect));

                document.querySelectorAll('[data-page-select]').forEach((select) => {
                    select.addEventListener('change', () => {
                        const selectedOption = select.selectedOptions[0];
                        const form = select.closest('form');

                        if (!form || !selectedOption || !selectedOption.value) {
                            return;
                        }

                        const titleField = form.querySelector(select.dataset.titleField);
                        const urlField = form.querySelector(select.dataset.urlField);

                        if (titleField && !titleField.value.trim()) {
                            titleField.value = selectedOption.dataset.title ?? '';
                        }

                        if (urlField) {
                            urlField.value = selectedOption.dataset.url ?? '';
                        }
                    });
                });
            </script>
=======
            window.closeModal = closeModal;
            window.openEditModal = openEditModal;
        </script>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    @endpush
@endsection
