<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PartnerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'url' => 'nullable|url|max:500',
            'order' => 'nullable|integer|min:0|max:999',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'The image must be a valid image file (JPEG, PNG, JPG, SVG, WEBP).',
            'image.max' => 'The image may not be greater than 2MB.',
            'url.url' => 'The URL must be a valid URL (e.g., https://example.com).',
        ];
    }
}

