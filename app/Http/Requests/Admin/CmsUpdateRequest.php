<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
<<<<<<< HEAD
use Illuminate\Support\Str;
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

class CmsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $pageId = $this->route('cms');
        return [
            'title' => 'required|string|max:255',
            'slug' => "nullable|alpha_dash|unique:cms_pages,slug,{$pageId}",
            'content' => 'required|string',
<<<<<<< HEAD
            'about_images' => 'nullable|array',
            'about_images.*' => 'nullable',
            'about_images.*.*' => 'nullable',
            'about_images.profiles.*.img' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'about_images.what_we_do.*.img' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'about_images.how_we_do.*.img' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'about_images.video_bg' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'page_images' => 'nullable|array',
            'page_images.contact_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'status' => 'required|in:active,inactive',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
<<<<<<< HEAD
            'seo_meta' => 'nullable|array',
            'seo_meta.focus_keyword' => 'nullable|string|max:255',
            'seo_meta.secondary_keywords_text' => 'nullable|string|max:1000',
            'seo_meta.keywords' => 'nullable|string|max:1000',
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
        ];
    }

    protected function prepareForValidation(): void
    {
        $slugSource = trim((string) ($this->input('slug') ?: $this->input('title')));

        $this->merge([
            'slug' => $slugSource !== '' ? Str::slug($slugSource) : null,
        ]);
    }

=======
        ];
    }

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'content.required' => 'The content is required.',
            'slug.unique' => 'The slug has already been taken.',
        ];
    }
}

