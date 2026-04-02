<?php

if (! function_exists('image_url')) {
    function image_url($image, string $size = 'large', ?string $fallback = null): string
    {
        $fallback ??= asset('assets/img/product/default.jpg');

        if (! $image) {
            return $fallback;
        }

        return app(\App\Services\ImageUploadService::class)->getImageUrl($image, $size) ?? $fallback;
    }
}

if (! function_exists('image_path')) {
    function image_path($image, string $size = 'large'): ?string
    {
        if (! $image) {
            return null;
        }

        return app(\App\Services\ImageUploadService::class)->getRelativePath($image, $size);
    }
}

if (! function_exists('image_srcset')) {
    function image_srcset($image): string
    {
        if (! $image) {
            return '';
        }

        return app(\App\Services\ImageUploadService::class)->getImageSrcSet($image) ?? '';
    }
}

