<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'vendor_id' => 'nullable|exists:vendors,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', Rule::unique('products')->ignore($productId)],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($productId)],
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock' => 'required|integer|min:0',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive,draft',
            'featured' => 'boolean',
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'material_id' => 'nullable|exists:materials,id',
            'color_id' => 'nullable|exists:colors,id',
            'warranty_months' => 'nullable|integer|min:0',
            'assembly_required' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'images.*' => 'image|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'sale_price.lt' => 'Sale price must be less than regular price.',
        ];
    }
}
