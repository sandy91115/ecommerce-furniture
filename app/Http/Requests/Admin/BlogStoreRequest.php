<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogStoreRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:blogs,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'published_at' => 'nullable|date',
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

