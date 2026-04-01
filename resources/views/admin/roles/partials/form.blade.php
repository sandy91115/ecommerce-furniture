@php
    $selectedPermissionNames = collect(old('permissions', $selectedPermissions ?? []))
        ->map(fn ($permissionName) => (string) $permissionName)
        ->unique()
        ->values()
        ->all();

    $roleNameValue = old('name', $role->name ?? '');
    $roleDisplayName = $roleNameValue !== ''
        ? \Illuminate\Support\Str::headline(str_replace('_', ' ', $roleNameValue))
        : 'New Role';
@endphp

<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pageTitle }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $pageDescription }}</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center rounded-2xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Roles
        </a>
    </div>

    <form action="{{ $formAction }}" method="POST" class="grid gap-6 xl:grid-cols-[340px,minmax(0,1fr)]">
        @csrf
        @if(($formMethod ?? 'POST') !== 'POST')
            @method($formMethod)
        @endif

        <aside class="space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Role Details</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Role identity ko yahan define karo. System roles ka name lock rahega.</p>

                <div class="mt-6 space-y-4">
                    <div>
                        <label for="roleName" class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Role Name</label>
                        @if($isSystemRole)
                        <input id="roleName" type="text" value="{{ $roleDisplayName }}" disabled class="w-full rounded-2xl border border-gray-200 bg-gray-100 px-4 py-3 text-sm font-medium text-gray-600 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-200">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">System role ka internal name editable nahi hai.</p>
                        @else
                        <input id="roleName" name="name" type="text" value="{{ $roleNameValue }}" required class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Example: Sales Manager, Operations Lead, Content Editor.</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-900/40">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Type</p>
                            <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $isSystemRole ? 'System Role' : 'Custom Role' }}</p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-900/40">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Assigned Users</p>
                            <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $role->users_count ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-900/40">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Current Selection</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white" data-selected-total>{{ count($selectedPermissionNames) }}</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">permissions selected</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
                @if($isLockedRole)
                <div class="mt-4 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-4 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-200">
                    Super Admin globally unlocked hai. Isliye is role par manual edits disabled hain.
                </div>
                @else
                <div class="mt-4 flex flex-col gap-3">
                    <button type="button" data-select-all class="inline-flex items-center justify-center rounded-2xl border border-blue-200 px-4 py-3 text-sm font-semibold text-blue-600 transition hover:bg-blue-50 dark:border-blue-800 dark:text-blue-300 dark:hover:bg-blue-900/20">
                        <i class="fas fa-check-double mr-2"></i>
                        Select All Permissions
                    </button>
                    <button type="button" data-clear-all class="inline-flex items-center justify-center rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-eraser mr-2"></i>
                        Clear All
                    </button>
                </div>
                @endif

                <div class="mt-6 flex flex-col gap-3">
                    @if(!$isLockedRole)
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>
                        {{ $submitLabel }}
                    </button>
                    @endif
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                </div>
            </div>
        </aside>

        <section class="space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Permission Groups</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Permissions ko module-wise group karke manage karo, taaki long multi-select ka clutter na rahe.</p>
                    </div>
                    <div class="relative w-full lg:max-w-xs">
                        <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                        <input type="text" data-permission-search placeholder="Search permissions..." class="w-full rounded-2xl border border-gray-300 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    </div>
                </div>
            </div>

            @foreach($permissionGroups as $groupKey => $permissions)
                @php
                    $groupLabel = \Illuminate\Support\Str::headline(str_replace('_', ' ', $groupKey));
                    $groupPermissionNames = $permissions->pluck('name')->all();
                    $selectedInGroup = count(array_intersect($groupPermissionNames, $selectedPermissionNames));
                @endphp
                <div class="permission-group rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800" data-group-label="{{ strtolower($groupLabel) }}">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $groupLabel }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <span data-group-selected-count="{{ $groupKey }}">{{ $selectedInGroup }}</span> of {{ $permissions->count() }} selected
                            </p>
                        </div>
                        @if(!$isLockedRole)
                        <div class="flex flex-wrap gap-2">
                            <button type="button" data-group-action="select" data-group-key="{{ $groupKey }}" class="rounded-2xl border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50 dark:border-blue-800 dark:text-blue-300 dark:hover:bg-blue-900/20">
                                Select Group
                            </button>
                            <button type="button" data-group-action="clear" data-group-key="{{ $groupKey }}" class="rounded-2xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
                                Clear Group
                            </button>
                        </div>
                        @endif
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
                        @foreach($permissions as $permission)
                            @php
                                $checked = in_array($permission->name, $selectedPermissionNames, true);
                                $permissionAction = \Illuminate\Support\Str::after($permission->name, $groupKey . '.');
                                $permissionAction = $permissionAction === $permission->name ? $permission->name : $permissionAction;
                                $permissionHint = \Illuminate\Support\Str::headline(str_replace('_', ' ', $permissionAction));
                            @endphp
                            <label class="permission-card flex items-start gap-4 rounded-2xl border px-4 py-4 transition {{ $checked ? 'border-blue-400 bg-blue-50/70 dark:border-blue-700 dark:bg-blue-900/20' : 'border-gray-200 bg-white hover:border-blue-200 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900/30 dark:hover:border-gray-600 dark:hover:bg-gray-900/60' }}" data-permission-label="{{ strtolower($permission->name . ' ' . $permissionHint) }}">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" data-group-key="{{ $groupKey }}" class="permission-checkbox mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ $checked ? 'checked' : '' }} {{ $isLockedRole ? 'disabled' : '' }}>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white break-words">{{ $permission->name }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $permissionHint }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = Array.from(document.querySelectorAll('.permission-checkbox'));
        const searchInput = document.querySelector('[data-permission-search]');
        const selectedTotal = document.querySelector('[data-selected-total]');

        function syncCardState(checkbox) {
            const card = checkbox.closest('.permission-card');
            if (!card) {
                return;
            }

            if (checkbox.checked) {
                card.classList.add('border-blue-400', 'bg-blue-50/70', 'dark:border-blue-700', 'dark:bg-blue-900/20');
                card.classList.remove('border-gray-200', 'bg-white', 'hover:border-blue-200', 'hover:bg-gray-50', 'dark:border-gray-700', 'dark:bg-gray-900/30', 'dark:hover:border-gray-600', 'dark:hover:bg-gray-900/60');
            } else {
                card.classList.remove('border-blue-400', 'bg-blue-50/70', 'dark:border-blue-700', 'dark:bg-blue-900/20');
                card.classList.add('border-gray-200', 'bg-white', 'hover:border-blue-200', 'hover:bg-gray-50', 'dark:border-gray-700', 'dark:bg-gray-900/30', 'dark:hover:border-gray-600', 'dark:hover:bg-gray-900/60');
            }
        }

        function updateCounts() {
            const checkedCount = checkboxes.filter((checkbox) => checkbox.checked).length;

            if (selectedTotal) {
                selectedTotal.textContent = checkedCount;
            }

            document.querySelectorAll('[data-group-selected-count]').forEach((counter) => {
                const groupKey = counter.getAttribute('data-group-selected-count');
                const groupCheckedCount = checkboxes.filter((checkbox) => checkbox.dataset.groupKey === groupKey && checkbox.checked).length;
                counter.textContent = groupCheckedCount;
            });

            checkboxes.forEach(syncCardState);
        }

        function setCheckboxCollection(collection, checked) {
            collection.forEach((checkbox) => {
                if (!checkbox.disabled) {
                    checkbox.checked = checked;
                }
            });

            updateCounts();
        }

        document.querySelectorAll('[data-select-all]').forEach((button) => {
            button.addEventListener('click', function () {
                setCheckboxCollection(checkboxes, true);
            });
        });

        document.querySelectorAll('[data-clear-all]').forEach((button) => {
            button.addEventListener('click', function () {
                setCheckboxCollection(checkboxes, false);
            });
        });

        document.querySelectorAll('[data-group-action]').forEach((button) => {
            button.addEventListener('click', function () {
                const groupKey = button.getAttribute('data-group-key');
                const shouldCheck = button.getAttribute('data-group-action') === 'select';
                const groupCheckboxes = checkboxes.filter((checkbox) => checkbox.dataset.groupKey === groupKey);

                setCheckboxCollection(groupCheckboxes, shouldCheck);
            });
        });

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateCounts);
        });

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const term = searchInput.value.trim().toLowerCase();

                document.querySelectorAll('.permission-group').forEach((group) => {
                    const groupMatches = group.dataset.groupLabel.includes(term);
                    let visibleCards = 0;

                    group.querySelectorAll('.permission-card').forEach((card) => {
                        const matches = groupMatches || term === '' || card.dataset.permissionLabel.includes(term);
                        card.classList.toggle('hidden', !matches);

                        if (matches) {
                            visibleCards += 1;
                        }
                    });

                    group.classList.toggle('hidden', visibleCards === 0);
                });
            });
        }

        updateCounts();
    });
</script>
@endpush
