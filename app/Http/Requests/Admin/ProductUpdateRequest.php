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
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'slug' => ['nullable', 'string', Rule::unique('products')->ignore($productId)],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($productId)],
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock' => 'required|integer|min:0',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'status' => 'required|in:pending,active,rejected',
            'product_type' => 'required|in:sell,quotation',
            'featured' => 'boolean',
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'material_id' => 'nullable|exists:materials,id',
            'color_id' => 'nullable|exists:colors,id',
            'warranty_months' => 'nullable|integer|min:0',
            'assembly_required' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_meta' => 'nullable|array',
            'seo_meta.focus_keyword' => 'nullable|string|max:255',
            'seo_meta.keywords' => 'nullable|string|max:1000',
            'seo_meta.secondary_keywords_text' => 'nullable|string|max:1000',
            'seo_meta.canonical_url' => 'nullable|url|max:255',
            'seo_meta.robots_index' => 'nullable|boolean',
            'seo_meta.robots_follow' => 'nullable|boolean',
            'seo_meta.noindex_reason' => 'nullable|string|max:255',
            'seo_meta.og_title' => 'nullable|string|max:255',
            'seo_meta.og_description' => 'nullable|string|max:500',
            'seo_meta.og_image' => 'nullable|url|max:255',
            'seo_meta.og_image_alt' => 'nullable|string|max:255',
            'seo_meta.twitter_title' => 'nullable|string|max:255',
            'seo_meta.twitter_description' => 'nullable|string|max:500',
            'seo_meta.twitter_image' => 'nullable|url|max:255',
            'seo_meta.schema_type' => 'nullable|string|max:100',
            'seo_meta.schema_data' => 'nullable|string|max:10000',
            'seo_meta.sitemap_priority' => 'nullable|numeric|min:0.1|max:1',
            'seo_meta.sitemap_changefreq' => 'nullable|in:always,hourly,daily,weekly,monthly,yearly,never',
            'extra_title' => 'nullable|string|max:255',
            'extra_description' => 'nullable|string',
            'tax_slab' => 'required|string|in:0,nil_rate,5,18,40,special_rates',
'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image_alts' => 'nullable|array',
            'image_alts.*' => 'nullable|string|max:255',
            'replace_images' => 'nullable|boolean',
            'delete_image' => 'nullable|array',
            'delete_image.*' => 'integer|exists:product_images,id',
            'featured_existing_image_id' => 'nullable|integer|exists:product_images,id',
            'image_meta' => 'nullable|array',
            'image_meta.*.alt' => 'nullable|string|max:255',
            'image_meta.*.title' => 'nullable|string|max:255',
            'image_meta.*.caption' => 'nullable|string|max:255',
            'image_meta.*.description' => 'nullable|string|max:1000',
            'image_meta.*.sort_order' => 'nullable|integer|min:0',
            'featured_image_index' => 'nullable|integer|min:0',
            'technical_specifications' => 'nullable|array',
            'technical_specifications.*.field' => 'nullable|string|max:255',
            'technical_specifications.*.value' => 'nullable|string|max:255',
            'technical_specifications.*.notes' => 'nullable|string|max:500',
            'customization_options' => 'nullable|array',
            'customization_options.*.category' => 'nullable|string|max:255',
            'customization_options.*.choices' => 'nullable|string|max:500',
            'customization_options.*.applies_to' => 'nullable|string|max:255',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:255',
            'faqs.*.answer' => 'nullable|string|max:1000',
            'product_rating' => 'nullable|numeric|min:0|max:5',
            'product_rating_count' => 'nullable|integer|min:0',
            'care_and_maintenance' => 'nullable|string',
            'shipping_details' => 'nullable|string',
            'product_reviews' => 'nullable|array',
            'product_reviews.*.id' => 'nullable|integer|exists:reviews,id',
            'product_reviews.*.reviewer_name' => 'nullable|string|max:255',
            'product_reviews.*.reviewer_email' => 'nullable|email|max:255',
            'product_reviews.*.rating' => 'nullable|integer|min:1|max:5',
            'product_reviews.*.title' => 'nullable|string|max:255',
            'product_reviews.*.comment' => 'nullable|string|max:1000',
            'product_reviews.*.status' => 'nullable|in:pending,approved,rejected',
            'delete_product_reviews' => 'nullable|array',
            'delete_product_reviews.*' => 'integer|exists:reviews,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A product with this title already exists. Please use a different title.',
            'slug.unique' => 'This slug is already taken. Try another or leave it blank.',
            'sku.unique' => 'This SKU is already in use. Each product must have a unique SKU.',
            'sale_price.lt' => 'Sale price must be less than regular price.',
        ];
    }
}
