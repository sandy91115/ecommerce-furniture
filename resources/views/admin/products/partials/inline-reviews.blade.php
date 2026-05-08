@php
    $productModel = $product ?? null;
    $oldReviews = old('product_reviews');

    if (is_array($oldReviews)) {
        $reviewRows = collect($oldReviews);
    } elseif ($productModel) {
        $reviewRows = $productModel->reviews
            ->sortByDesc('created_at')
            ->values()
            ->map(fn ($review) => [
                'id' => $review->id,
                'reviewer_name' => $review->reviewer_name ?: ($review->user->name ?? ''),
                'reviewer_email' => $review->reviewer_email,
                'rating' => $review->rating,
                'title' => $review->title,
                'comment' => $review->comment,
                'status' => $review->status,
            ]);
    } else {
        $reviewRows = collect([]);
    }

    if ($reviewRows->isEmpty()) {
        $reviewRows = collect([[
            'id' => '',
            'reviewer_name' => '',
            'reviewer_email' => '',
            'rating' => 5,
            'title' => '',
            'comment' => '',
            'status' => 'approved',
        ]]);
    }

    while ($reviewRows->count() < 3) {
        $reviewRows->push([
            'id' => '',
            'reviewer_name' => '',
            'reviewer_email' => '',
            'rating' => 5,
            'title' => '',
            'comment' => '',
            'status' => 'approved',
        ]);
    }
@endphp

<section class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5" data-inline-review-section data-next-index="{{ $reviewRows->count() }}">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Customer Reviews</h3>
            <p class="mt-1 text-sm text-gray-500">Add customer reviews for this product. Approved reviews appear in Customers Say on the product page.</p>
        </div>
        <button type="button" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700" data-add-inline-review>
            Add Review
        </button>
    </div>

    <div class="mt-5 space-y-4" data-inline-review-rows>
        @foreach($reviewRows as $index => $reviewRow)
            <div class="rounded-lg border border-gray-200 bg-white p-4" data-inline-review-row>
                <input type="hidden" name="product_reviews[{{ $index }}][id]" value="{{ $reviewRow['id'] ?? '' }}">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                        <input type="text" name="product_reviews[{{ $index }}][reviewer_name]" value="{{ $reviewRow['reviewer_name'] ?? '' }}" placeholder="e.g. Prem Bhatt" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="product_reviews[{{ $index }}][reviewer_email]" value="{{ $reviewRow['reviewer_email'] ?? '' }}" placeholder="Optional" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <select name="product_reviews[{{ $index }}][rating]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @for($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}" {{ (int) ($reviewRow['rating'] ?? 5) === $rating ? 'selected' : '' }}>{{ $rating }} Star{{ $rating > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="product_reviews[{{ $index }}][status]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach(['approved' => 'Approved', 'pending' => 'Pending', 'rejected' => 'Rejected'] as $value => $label)
                                <option value="{{ $value }}" {{ ($reviewRow['status'] ?? 'approved') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Review Title</label>
                        <input type="text" name="product_reviews[{{ $index }}][title]" value="{{ $reviewRow['title'] ?? '' }}" placeholder="e.g. Excellent product" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Review Comment</label>
                        <div class="flex gap-3">
                            <textarea name="product_reviews[{{ $index }}][comment]" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Write what customer said about this product">{{ $reviewRow['comment'] ?? '' }}</textarea>
                            <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-inline-review>Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <template data-inline-review-template>
        <div class="rounded-lg border border-gray-200 bg-white p-4" data-inline-review-row>
            <input type="hidden" data-name="product_reviews[__INDEX__][id]" value="">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                    <input type="text" data-name="product_reviews[__INDEX__][reviewer_name]" placeholder="e.g. Prem Bhatt" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" data-name="product_reviews[__INDEX__][reviewer_email]" placeholder="Optional" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <select data-name="product_reviews[__INDEX__][rating]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select data-name="product_reviews[__INDEX__][status]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Review Title</label>
                    <input type="text" data-name="product_reviews[__INDEX__][title]" placeholder="e.g. Excellent product" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Review Comment</label>
                    <div class="flex gap-3">
                        <textarea rows="2" data-name="product_reviews[__INDEX__][comment]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Write what customer said about this product"></textarea>
                        <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-inline-review>Remove</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</section>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                (() => {
                const namePattern = /\[\d+\]/;

                const assignNames = (row, index) => {
                    row.querySelectorAll('input, textarea, select').forEach((field) => {
                        const templateName = field.dataset.name || field.name;

                        if (!templateName) {
                            return;
                        }

                        field.dataset.name = templateName.replace(namePattern, '[__INDEX__]');
                        field.name = field.dataset.name.replace('__INDEX__', index);
                    });
                };

                const reindexReviews = (section) => {
                    section.querySelectorAll('[data-inline-review-row]').forEach((row, index) => assignNames(row, index));
                    section.dataset.nextIndex = String(section.querySelectorAll('[data-inline-review-row]').length);
                };

                document.querySelectorAll('[data-inline-review-section]').forEach((section) => {
                    if (section.dataset.inlineReviewInitialized === 'true') {
                        return;
                    }

                    section.dataset.inlineReviewInitialized = 'true';

                    const rows = section.querySelector('[data-inline-review-rows]');
                    const template = section.querySelector('[data-inline-review-template]');
                    const addButton = section.querySelector('[data-add-inline-review]');

                    reindexReviews(section);

                    addButton?.addEventListener('click', () => {
                        const index = Number(section.dataset.nextIndex || 0);
                        const row = template.content.firstElementChild.cloneNode(true);

                        assignNames(row, index);
                        rows.appendChild(row);
                        reindexReviews(section);
                        row.querySelector('input, textarea, select')?.focus();
                    });

                    rows?.addEventListener('click', (event) => {
                        const button = event.target.closest('[data-remove-inline-review]');

                        if (!button) {
                            return;
                        }

                        const row = button.closest('[data-inline-review-row]');
                        const idInput = row.querySelector('input[name$="[id]"]');

                        if (idInput && idInput.value) {
                            const deleteInput = document.createElement('input');
                            deleteInput.type = 'hidden';
                            deleteInput.name = 'delete_product_reviews[]';
                            deleteInput.value = idInput.value;
                            section.appendChild(deleteInput);
                        }

                        row.remove();
                        reindexReviews(section);
                    });
                });

                document.querySelectorAll('form').forEach((form) => {
                    form.addEventListener('submit', () => {
                        form.querySelectorAll('[data-inline-review-section]').forEach(reindexReviews);
                    });
                });
                })();
            });
        </script>
    @endpush
@endonce
