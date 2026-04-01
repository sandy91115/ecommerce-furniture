@php
    $resolveTitle = function ($item) {
        return $item->name
            ?? $item->title
            ?? $item->store_name
            ?? $item->order_number
            ?? $item->code
            ?? $item->email
            ?? $item->slug
            ?? 'Record #' . $item->id;
    };

    $resolveMeta = function ($item) {
        return $item->email
            ?? $item->slug
            ?? $item->code
            ?? $item->order_number
            ?? $item->store_slug
            ?? null;
    };
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Item</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Deleted At</th>
                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
            @forelse($items as $item)
                @php
                    $title = $resolveTitle($item);
                    $meta = $resolveMeta($item);
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 align-top">
                        <div class="font-medium text-gray-900">{{ $title }}</div>
                        @if($meta && $meta !== $title)
                            <div class="mt-1 text-sm text-gray-500">{{ $meta }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ optional($item->deleted_at)->format('d M Y, h:i A') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <form action="{{ route('admin.trash.restore', [$type, $item->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                                    Restore
                                </button>
                            </form>

                            @if($canForceDelete)
                                <form action="{{ route('admin.trash.force-delete', [$type, $item->id]) }}" method="POST" onsubmit="return confirm('After delete you cant restore it.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                                        Delete Permanently
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-medium text-amber-700">Super Admin only</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                        No {{ Str::of($type)->replace('_', ' ')->lower() }} items found in Recycle Bin.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

