@php
    $seoMetadata = $seoMetadata ?? null;
    $includeTitleFields = $includeTitleFields ?? true;
    $titleFallback = $titleFallback ?? '';
    $descriptionFallback = $descriptionFallback ?? '';
    $canonicalFallback = $canonicalFallback ?? '';
    $schemaType = $schemaType ?? ($seoMetadata->schema_type ?? 'WebPage');
    $secondaryKeywords = old('seo_meta.secondary_keywords_text', is_array($seoMetadata?->secondary_keywords ?? null) ? implode(', ', $seoMetadata->secondary_keywords) : '');
    $schemaJson = old('seo_meta.schema_data', $seoMetadata?->schema_data ? json_encode($seoMetadata->schema_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '');
@endphp

<section class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm" data-seo-panel>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Advanced SEO</h3>
            <p class="text-sm text-gray-500">Search snippet, social cards, robots, canonical, sitemap, and schema controls.</p>
        </div>
        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700" data-seo-score>Score 0</span>
    </div>

    @if($includeTitleFields)
        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SEO Title</label>
                <input type="text" name="seo_meta[title]" value="{{ old('seo_meta.title', $seoMetadata->title ?? $titleFallback) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" data-seo-title>
                <p class="mt-1 text-xs text-gray-500"><span data-seo-title-count>0</span> characters. Ideal 45-60.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                <textarea name="seo_meta[description]" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" data-seo-description>{{ old('seo_meta.description', $seoMetadata->description ?? $descriptionFallback) }}</textarea>
                <p class="mt-1 text-xs text-gray-500"><span data-seo-description-count>0</span> characters. Ideal 120-160.</p>
            </div>
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Focus Keyword</label>
            <input type="text" name="seo_meta[focus_keyword]" value="{{ old('seo_meta.focus_keyword', $seoMetadata->focus_keyword ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" data-seo-focus>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Keywords</label>
            <input type="text" name="seo_meta[secondary_keywords_text]" value="{{ $secondaryKeywords }}" placeholder="comma separated keywords" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
            <input type="text" name="seo_meta[keywords]" value="{{ old('seo_meta.keywords', $seoMetadata->keywords ?? '') }}" placeholder="optional, comma separated" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Canonical URL</label>
            <input type="url" name="seo_meta[canonical_url]" value="{{ old('seo_meta.canonical_url', $seoMetadata->canonical_url ?? '') }}" placeholder="{{ $canonicalFallback ?: 'Auto generated' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" data-seo-canonical>
        </div>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
            <input type="hidden" name="seo_meta[robots_index]" value="0">
            <input type="checkbox" name="seo_meta[robots_index]" value="1" {{ old('seo_meta.robots_index', $seoMetadata->robots_index ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            Allow search engines to index
        </label>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
            <input type="hidden" name="seo_meta[robots_follow]" value="0">
            <input type="checkbox" name="seo_meta[robots_follow]" value="1" {{ old('seo_meta.robots_follow', $seoMetadata->robots_follow ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            Follow links on page
        </label>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Noindex Reason</label>
            <input type="text" name="seo_meta[noindex_reason]" value="{{ old('seo_meta.noindex_reason', $seoMetadata->noindex_reason ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Open Graph Title</label>
            <input type="text" name="seo_meta[og_title]" value="{{ old('seo_meta.og_title', $seoMetadata->og_title ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Open Graph Image URL</label>
            <input type="url" name="seo_meta[og_image]" value="{{ old('seo_meta.og_image', $seoMetadata->og_image ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Open Graph Description</label>
            <textarea name="seo_meta[og_description]" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('seo_meta.og_description', $seoMetadata->og_description ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">OG Image Alt</label>
            <input type="text" name="seo_meta[og_image_alt]" value="{{ old('seo_meta.og_image_alt', $seoMetadata->og_image_alt ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Image URL</label>
            <input type="url" name="seo_meta[twitter_image]" value="{{ old('seo_meta.twitter_image', $seoMetadata->twitter_image ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Title</label>
            <input type="text" name="seo_meta[twitter_title]" value="{{ old('seo_meta.twitter_title', $seoMetadata->twitter_title ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Description</label>
            <textarea name="seo_meta[twitter_description]" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('seo_meta.twitter_description', $seoMetadata->twitter_description ?? '') }}</textarea>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Schema Type</label>
            <input type="text" name="seo_meta[schema_type]" value="{{ old('seo_meta.schema_type', $schemaType) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sitemap Priority</label>
            <input type="number" min="0.1" max="1" step="0.1" name="seo_meta[sitemap_priority]" value="{{ old('seo_meta.sitemap_priority', $seoMetadata->sitemap_priority ?? '') }}" placeholder="0.8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Change Frequency</label>
            <select name="seo_meta[sitemap_changefreq]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @foreach(['', 'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'] as $freq)
                    <option value="{{ $freq }}" {{ old('seo_meta.sitemap_changefreq', $seoMetadata->sitemap_changefreq ?? '') === $freq ? 'selected' : '' }}>{{ $freq ?: 'Auto' }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Custom Schema JSON-LD</label>
            <textarea name="seo_meta[schema_data]" rows="8" class="font-mono text-sm w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Paste a valid JSON-LD object here">{{ $schemaJson }}</textarea>
        </div>
    </div>

    <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <p class="text-xs font-semibold uppercase text-gray-500">Google Preview</p>
        <p class="mt-2 text-lg text-blue-700" data-seo-preview-title>{{ ($seoMetadata?->title ?? $titleFallback) ?: 'SEO title preview' }}</p>
        <p class="text-sm text-green-700 break-all" data-seo-preview-url>{{ ($seoMetadata?->canonical_url ?? $canonicalFallback) ?: url()->current() }}</p>
        <p class="mt-1 text-sm text-gray-700" data-seo-preview-description>{{ ($seoMetadata?->description ?? $descriptionFallback) ?: 'Meta description preview will appear here.' }}</p>
        <ul class="mt-3 grid grid-cols-1 gap-1 text-xs text-gray-600 md:grid-cols-2" data-seo-checklist></ul>
    </div>
</section>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-seo-panel]').forEach(function (panel) {
        const titleInput = panel.querySelector('[data-seo-title]') || document.querySelector('input[name="seo_title"], input[name="meta_title"], input[name="title"]');
        const descriptionInput = panel.querySelector('[data-seo-description]') || document.querySelector('textarea[name="seo_description"], input[name="seo_description"], textarea[name="meta_description"], textarea[name="excerpt"]');
        const focusInput = panel.querySelector('[data-seo-focus]');
        const canonicalInput = panel.querySelector('[data-seo-canonical]');
        const titleCount = panel.querySelector('[data-seo-title-count]');
        const descriptionCount = panel.querySelector('[data-seo-description-count]');
        const score = panel.querySelector('[data-seo-score]');
        const previewTitle = panel.querySelector('[data-seo-preview-title]');
        const previewDescription = panel.querySelector('[data-seo-preview-description]');
        const previewUrl = panel.querySelector('[data-seo-preview-url]');
        const checklist = panel.querySelector('[data-seo-checklist]');

        function mark(label, pass) {
            return '<li class="' + (pass ? 'text-green-700' : 'text-amber-700') + '">' + (pass ? 'Passed: ' : 'Needs work: ') + label + '</li>';
        }

        function refresh() {
            const title = titleInput ? titleInput.value.trim() : '';
            const description = descriptionInput ? descriptionInput.value.trim() : '';
            const focus = focusInput ? focusInput.value.trim().toLowerCase() : '';
            const canonical = canonicalInput ? canonicalInput.value.trim() : '';
            const titleOk = title.length >= 45 && title.length <= 60;
            const descriptionOk = description.length >= 120 && description.length <= 160;
            const keywordTitle = !focus || title.toLowerCase().includes(focus);
            const keywordDescription = !focus || description.toLowerCase().includes(focus);
            const canonicalOk = canonical === '' || canonical.startsWith('http');
            let value = 0;

            value += titleOk ? 25 : (title.length ? 12 : 0);
            value += descriptionOk ? 25 : (description.length ? 12 : 0);
            value += keywordTitle ? 15 : 0;
            value += keywordDescription ? 15 : 0;
            value += canonicalOk ? 10 : 0;
            value += 10;

            if (titleCount) titleCount.textContent = title.length;
            if (descriptionCount) descriptionCount.textContent = description.length;
            if (score) {
                score.textContent = 'Score ' + Math.min(100, value);
                score.className = 'rounded-full px-3 py-1 text-xs font-semibold ' + (value >= 80 ? 'bg-green-100 text-green-700' : value >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700');
            }
            if (previewTitle) previewTitle.textContent = title || 'SEO title preview';
            if (previewDescription) previewDescription.textContent = description || 'Meta description preview will appear here.';
            if (previewUrl && canonical) previewUrl.textContent = canonical;
            if (checklist) {
                checklist.innerHTML = [
                    mark('title length 45-60 characters', titleOk),
                    mark('description length 120-160 characters', descriptionOk),
                    mark('focus keyword in title', keywordTitle),
                    mark('focus keyword in description', keywordDescription),
                    mark('canonical URL valid or auto generated', canonicalOk),
                    mark('schema enabled', true)
                ].join('');
            }
        }

        [titleInput, descriptionInput, focusInput, canonicalInput].forEach(function (input) {
            if (input) input.addEventListener('input', refresh);
        });
        refresh();
    });
});
</script>
@endpush
@endonce
