<?php

if (! function_exists('image_url')) {
    function image_url($image, string $size = 'large', ?string $fallback = null): string
    {
        $defaultSvg = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDMwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjNGNEY1Ii8+CjxwYXRoIGQ9Ik0xMDAgMTUwIEwxNTAgMTUwIEwxNTAgMTAwIEwyMDAgMTAwIEwyMDAgMjAwIEwxNTAgMjAwIEwxNTAgMjUwIEwxMDAgMjUwIEwxMDAgMjAwIEw1MCAyMDAgTDUwIDE1MCBMNTAgMTAwIEwxMDAgMTAwIFoiIGZpbGw9IiNBOUI5QjIiIHN0cm9rZT0iIzdBN0E4QSIgc3Ryb2tlLXdpZHRoPSIyIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTU1IiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTgiIGZpbGw9IiM3QTdBOEUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIwLjM1ZW0iPk5vIEltYWdlPC90ZXh0Pgo8L3N2Zz4K';
        $fallback ??= $defaultSvg;

        if (! $image) {
            return $fallback;
        }

        if (is_string($image)) {
            $image = trim($image);

            if ($image === '') {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://', '//', 'data:'])) {
                return $image;
            }

            if (\Illuminate\Support\Str::startsWith($image, ['storage/', 'assets/'])) {
                return asset(ltrim($image, '/'));
            }

            return asset('storage/' . ltrim($image, '/'));
        }

        if ($image instanceof \App\Models\ProductImage) {
            return app(\App\Services\ImageUploadService::class)->getImageUrl($image, $size) ?? $fallback;
        }

        return $fallback;
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
