@php
    $rawTags = $tags ?? old('tags', []);

    if (is_string($rawTags)) {
        $rawTags = preg_split('/[\r\n,]+/', $rawTags) ?: [];
    }

    $tagValues = collect(is_array($rawTags) ? $rawTags : [])
        ->filter(fn ($tag) => is_string($tag) || is_numeric($tag))
        ->map(fn ($tag) => trim(preg_replace('/\s+/', ' ', (string) $tag)))
        ->filter()
        ->unique(fn ($tag) => strtolower($tag))
        ->values();

    $fieldId = $fieldId ?? 'blog-tags';
@endphp

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>

    <div id="{{ $fieldId }}" class="js-tag-field rounded-xl border border-gray-300 bg-gray-50 p-4">
        <div class="js-tag-list flex flex-wrap gap-2 {{ $tagValues->isNotEmpty() ? 'mb-3' : '' }}">
            @foreach($tagValues as $tag)
                <span class="js-tag-chip inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800" data-tag="{{ \Illuminate\Support\Str::lower($tag) }}">
                    <span>{{ $tag }}</span>
                    <button type="button" class="js-remove-tag text-blue-700 transition hover:text-blue-900" aria-label="Remove {{ $tag }}">&times;</button>
                    <input type="hidden" name="tags[]" value="{{ $tag }}">
                </span>
            @endforeach
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <input
                type="text"
                name="tags_input"
                class="js-tag-input w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:flex-1"
                placeholder="Type a tag and press Enter or comma"
            >
            <button type="button" class="js-tag-add rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                Add Tag
            </button>
        </div>
    </div>

    <p class="mt-2 text-xs text-gray-500">Use Enter, comma, or paste a comma-separated list. Duplicate tags are skipped automatically.</p>

    @error('tags')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('tags.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

@once
    @push('scripts')
       
    @endpush
@endonce
