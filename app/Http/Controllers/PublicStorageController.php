<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicStorageController extends Controller
{
    public function show(string $path): BinaryFileResponse
    {
        $absoluteRoot = realpath(Storage::disk('public')->path(''));
        $absolutePath = realpath(Storage::disk('public')->path($path));

        if (! $absoluteRoot || ! $absolutePath) {
            abort(404);
        }

        $normalizedRoot = rtrim($absoluteRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (! str_starts_with($absolutePath, $normalizedRoot)) {
            abort(404);
        }

        return response()->file($absolutePath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
