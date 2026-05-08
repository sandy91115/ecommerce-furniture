<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tags' => $this->normalizeTags([
                ...$this->extractTags($this->input('tags', [])),
                ...$this->extractTags($this->input('tags_input')),
            ]),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
<<<<<<< HEAD
        $blog = $this->route('blog');
        $blogId = is_object($blog) ? $blog->getKey() : $blog;
=======
        $blogId = $this->route('blog:id'); // For model binding Blog $blog
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

        return [
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', Rule::unique('blogs', 'slug')->ignore($blogId)],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
<<<<<<< HEAD
'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'published_at' => 'nullable|date',
            'seo_meta' => 'nullable|array',
            'seo_meta.title' => 'nullable|string|max:255',
            'seo_meta.description' => 'nullable|string|max:500',
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
=======
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'published_at' => 'nullable|date',
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        ];
    }

    protected function normalizeTags(mixed $tags): array
    {
        $tags = $this->extractTags($tags);

        $normalized = [];
        $seen = [];

        foreach ($tags as $tag) {
            if (! is_string($tag) && ! is_numeric($tag)) {
                continue;
            }

            $value = trim(preg_replace('/\s+/', ' ', (string) $tag));

            if ($value === '') {
                continue;
            }

            $key = Str::lower($value);

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $normalized[] = $value;
        }

        return $normalized;
    }

    protected function extractTags(mixed $tags): array
    {
        if (is_string($tags)) {
            return preg_split('/[\r\n,]+/', $tags) ?: [];
        }

        return is_array($tags) ? $tags : [];
    }
}
<<<<<<< HEAD
=======

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
