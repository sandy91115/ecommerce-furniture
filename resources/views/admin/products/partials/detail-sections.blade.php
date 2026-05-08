@php
    $productModel = $product ?? null;
    $normalizeRows = function ($rows) {
        if ($rows instanceof \Illuminate\Support\Collection) {
            return $rows->all();
        }

        if (is_string($rows)) {
            $decoded = json_decode($rows, true);
            return is_array($decoded) ? $decoded : [];
        }

        return is_array($rows) ? $rows : [];
    };

    $technicalSpecs = $normalizeRows(old('technical_specifications', $productModel?->technical_specifications ?? []));
    $customizationOptions = $normalizeRows(old('customization_options', $productModel?->customization_options ?? []));
    $faqs = $normalizeRows(old('faqs', $productModel?->faqs ?? []));

    $technicalSpecs = count($technicalSpecs ?: []) ? $technicalSpecs : [['field' => '', 'value' => '', 'notes' => '']];
    $customizationOptions = count($customizationOptions ?: []) ? $customizationOptions : [['category' => '', 'choices' => '', 'applies_to' => '']];
    $faqs = count($faqs ?: []) ? $faqs : [['question' => '', 'answer' => '']];

    while (count($technicalSpecs) < 3) {
        $technicalSpecs[] = ['field' => '', 'value' => '', 'notes' => ''];
    }

    while (count($customizationOptions) < 3) {
        $customizationOptions[] = ['category' => '', 'choices' => '', 'applies_to' => ''];
    }

    while (count($faqs) < 3) {
        $faqs[] = ['question' => '', 'answer' => ''];
    }
@endphp

<div class="mt-8 space-y-8">
    <section class="rounded-xl border border-gray-200 bg-gray-50 p-5" data-repeat-section data-next-index="{{ count($technicalSpecs) }}">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Technical Specifications</h3>
                <p class="mt-1 text-sm text-gray-500">Add product specification rows shown in the Technical Specifications tab.</p>
            </div>
            <button type="button" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700" data-add-row>
                Add Field
            </button>
        </div>

        <div class="mt-5 space-y-4" data-rows>
            @foreach($technicalSpecs as $index => $spec)
                <div class="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-3" data-row>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specs Field</label>
                        <input type="text" name="technical_specifications[{{ $index }}][field]" value="{{ $spec['field'] ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Example Value</label>
                        <input type="text" name="technical_specifications[{{ $index }}][value]" value="{{ $spec['value'] ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <div class="flex gap-3">
                            <input type="text" name="technical_specifications[{{ $index }}][notes]" value="{{ $spec['notes'] ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-row>Remove</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <template data-row-template>
            <div class="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-3" data-row>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Specs Field</label>
                    <input type="text" data-name="technical_specifications[__INDEX__][field]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Example Value</label>
                    <input type="text" data-name="technical_specifications[__INDEX__][value]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <div class="flex gap-3">
                        <input type="text" data-name="technical_specifications[__INDEX__][notes]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-row>Remove</button>
                    </div>
                </div>
            </div>
        </template>
    </section>

    <section class="rounded-xl border border-gray-200 bg-gray-50 p-5" data-repeat-section data-next-index="{{ count($customizationOptions) }}">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Customization Options</h3>
                <p class="mt-1 text-sm text-gray-500">Each option row appears in the Customization Options tab.</p>
            </div>
            <button type="button" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700" data-add-row>
                Add Section
            </button>
        </div>

        <div class="mt-5 space-y-4" data-rows>
            @foreach($customizationOptions as $index => $option)
                <div class="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-3" data-row>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Option Category</label>
                        <input type="text" name="customization_options[{{ $index }}][category]" value="{{ $option['category'] ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Choices Available</label>
                        <input type="text" name="customization_options[{{ $index }}][choices]" value="{{ $option['choices'] ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Applies To</label>
                        <div class="flex gap-3">
                            <input type="text" name="customization_options[{{ $index }}][applies_to]" value="{{ $option['applies_to'] ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-row>Remove</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <template data-row-template>
            <div class="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-3" data-row>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Option Category</label>
                    <input type="text" data-name="customization_options[__INDEX__][category]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Choices Available</label>
                    <input type="text" data-name="customization_options[__INDEX__][choices]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Applies To</label>
                    <div class="flex gap-3">
                        <input type="text" data-name="customization_options[__INDEX__][applies_to]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-row>Remove</button>
                    </div>
                </div>
            </div>
        </template>
    </section>

    <section class="rounded-xl border border-gray-200 bg-gray-50 p-5" data-repeat-section data-next-index="{{ count($faqs) }}">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">FAQs</h3>
                <p class="mt-1 text-sm text-gray-500">Questions and answers appear in the FAQ tab.</p>
            </div>
            <button type="button" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700" data-add-row>
                Add More FAQs
            </button>
        </div>

        <div class="mt-5 space-y-4" data-rows>
            @foreach($faqs as $index => $faq)
                <div class="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-2" data-row>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                        <input type="text" name="faqs[{{ $index }}][question]" value="{{ $faq['question'] ?? '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Answer</label>
                        <div class="flex gap-3">
                            <textarea name="faqs[{{ $index }}][answer]" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $faq['answer'] ?? '' }}</textarea>
                            <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-row>Remove</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <template data-row-template>
            <div class="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-2" data-row>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                    <input type="text" data-name="faqs[__INDEX__][question]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Answer</label>
                    <div class="flex gap-3">
                        <textarea rows="2" data-name="faqs[__INDEX__][answer]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        <button type="button" class="shrink-0 rounded-lg border border-red-200 px-3 text-red-600 hover:bg-red-50" data-remove-row>Remove</button>
                    </div>
                </div>
            </div>
        </template>
    </section>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                (() => {
                const namePattern = /\[\d+\]/;

                const rowFields = (row) => row.querySelectorAll('input, textarea, select');

                const assignFieldNames = (row, index) => {
                    rowFields(row).forEach((field) => {
                        const templateName = field.dataset.name || field.name;

                        if (!templateName) {
                            return;
                        }

                        field.dataset.name = templateName.replace(namePattern, '[__INDEX__]');
                        field.name = field.dataset.name.replace('__INDEX__', index);
                    });
                };

                const reindexSection = (section) => {
                    section.querySelectorAll('[data-row]').forEach((row, index) => assignFieldNames(row, index));
                    section.dataset.nextIndex = String(section.querySelectorAll('[data-row]').length);
                };

                document.querySelectorAll('[data-repeat-section]').forEach((section) => {
                    if (section.dataset.repeatInitialized === 'true') {
                        return;
                    }

                    const rows = section.querySelector('[data-rows]');
                    const template = section.querySelector('[data-row-template]');
                    const addButton = section.querySelector('[data-add-row]');

                    if (!rows || !template || !addButton) {
                        return;
                    }

                    section.dataset.repeatInitialized = 'true';
                    reindexSection(section);

                    const refreshRemoveButtons = () => {
                        const removeButtons = rows.querySelectorAll('[data-remove-row]');
                        removeButtons.forEach((button) => {
                            button.disabled = removeButtons.length <= 1;
                            button.classList.toggle('opacity-40', removeButtons.length <= 1);
                            button.classList.toggle('cursor-not-allowed', removeButtons.length <= 1);
                        });
                    };

                    addButton.addEventListener('click', () => {
                        const index = Number(section.dataset.nextIndex || 0);
                        const row = template.content.firstElementChild.cloneNode(true);

                        assignFieldNames(row, index);
                        rows.appendChild(row);
                        reindexSection(section);
                        row.querySelector('input, textarea, select')?.focus();
                        refreshRemoveButtons();
                    });

                    rows.addEventListener('click', (event) => {
                        const button = event.target.closest('[data-remove-row]');

                        if (!button || button.disabled) {
                            return;
                        }

                        button.closest('[data-row]')?.remove();
                        reindexSection(section);
                        refreshRemoveButtons();
                    });

                    refreshRemoveButtons();
                });

                document.querySelectorAll('form').forEach((form) => {
                    form.addEventListener('submit', () => {
                        form.querySelectorAll('[data-repeat-section]').forEach(reindexSection);
                    });
                });
                })();
            });
        </script>
    @endpush
@endonce
