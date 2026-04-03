<?php

namespace App\Services;

use App\Jobs\ProcessProductImage;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use RuntimeException;
use Throwable;

class ImageUploadService
{
    public const PUBLIC_DISK = 'public';
    public const TEMP_DISK = 'local';

    private const TEMP_ROOT = 'tmp/product-images';
    private const PRODUCT_ROOT = 'uploads/products';
    private const WEBP_QUALITY = 75;

    private const VARIANTS = [
        'thumb' => 200,
        'medium' => 500,
        'large' => 1000,
    ];

    /**
     * Store uploaded files privately, then queue background processing.
     *
     * @param  iterable<UploadedFile>  $files
     * @param  array<int, string|null>  $altTexts
     * @return array<int, array<string, mixed>>
     */
    public function queueProductImages(Product|int $product, iterable $files, int $featuredIndex = 0, array $altTexts = []): array
    {
        $productId = $product instanceof Product ? (int) $product->getKey() : (int) $product;
        $files = $this->normalizeFiles($files);

        if ($files->isEmpty()) {
            return [];
        }

        $featuredIndex = max(0, min($featuredIndex, $files->count() - 1));
        $queuedImages = [];
        $tempDisk = Storage::disk(self::TEMP_DISK);
        $tempDisk->makeDirectory($this->temporaryDirectory($productId));

        foreach ($files as $index => $file) {
            $this->assertSupportedImage($file);

            $originalName = trim((string) $file->getClientOriginalName()) ?: 'product-image.' . $file->getClientOriginalExtension();
            $tempFilename = $this->buildTemporaryFilename($productId, $originalName, $file->getClientOriginalExtension());
            $tempPath = $tempDisk->putFileAs($this->temporaryDirectory($productId), $file, $tempFilename);

            if (! is_string($tempPath) || $tempPath === '') {
                throw new RuntimeException("Unable to stage image [{$originalName}] for background processing.");
            }

            $altText = $this->resolveAltText($originalName, $altTexts[$index] ?? null);

            if ($this->shouldProcessSynchronously()) {
                $this->processQueuedProductImage(
                    productId: $productId,
                    tempPath: $tempPath,
                    originalName: $originalName,
                    altText: $altText,
                    isFeatured: $index === $featuredIndex,
                );
            } else {
                ProcessProductImage::dispatch(
                    productId: $productId,
                    tempPath: $tempPath,
                    originalName: $originalName,
                    altText: $altText,
                    isFeatured: $index === $featuredIndex,
                )->afterCommit();
            }

            $queuedImages[] = [
                'product_id' => $productId,
                'temp_path' => $tempPath,
                'original_name' => $originalName,
                'alt' => $altText,
                'is_featured' => $index === $featuredIndex,
            ];
        }

        return $queuedImages;
    }

    public function processQueuedProductImage(
        int $productId,
        string $tempPath,
        string $originalName,
        ?string $altText = null,
        bool $isFeatured = false,
    ): ?ProductImage {
        if (! Product::query()->whereKey($productId)->exists()) {
            $this->deleteTemporaryUpload($tempPath);

            return null;
        }

        $tempDisk = Storage::disk(self::TEMP_DISK);

        if (! $tempDisk->exists($tempPath)) {
            throw new RuntimeException("Temporary image not found at [{$tempPath}].");
        }

        $publicDisk = Storage::disk(self::PUBLIC_DISK);
        $productDirectory = $this->productDirectory($productId);
        $publicDisk->makeDirectory($productDirectory);

        $baseFilename = $this->buildFinalFilename($productId, $originalName);
        $generatedPaths = [];

        try {
            $sourceImage = $this->imageManager()
                ->read($tempDisk->path($tempPath))
                ->orient();

            foreach (self::VARIANTS as $variant => $width) {
                $variantImage = clone $sourceImage;
                $encodedImage = $variantImage->scaleDown(width: $width)->toWebp(self::WEBP_QUALITY);
                $relativePath = $this->variantRelativePath($productId, $baseFilename, $variant);

                $publicDisk->put($relativePath, (string) $encodedImage);
                $generatedPaths[$variant] = $relativePath;
            }

            if ($isFeatured) {
                ProductImage::query()
                    ->where('product_id', $productId)
                    ->update(['featured' => false]);
            }

            return ProductImage::query()->create([
                'product_id' => $productId,
                'path' => $generatedPaths['large'],
                'base_filename' => $baseFilename,
                'original_name' => $originalName,
                'alt' => $this->resolveAltText($originalName, $altText),
                'featured' => $isFeatured,
            ]);
        } catch (Throwable $exception) {
            $this->deletePaths($generatedPaths);

            throw $exception;
        } finally {
            $this->deleteTemporaryUpload($tempPath);
        }
    }

    public function deleteImage(ProductImage $image, bool $deleteRecord = true): void
    {
        $this->deletePaths($this->allRelativePaths($image));
        $this->cleanupEmptyDirectory($this->productDirectory((int) $image->product_id), self::PUBLIC_DISK);

        if ($deleteRecord && $image->exists) {
            $image->delete();
        }
    }

    public function deleteProductImages(Product $product): void
    {
        $product->loadMissing('images');

        foreach ($product->images as $image) {
            $this->deleteImage($image, deleteRecord: false);
        }

        $product->images()->delete();
        $this->cleanupEmptyDirectory($this->productDirectory((int) $product->getKey()), self::PUBLIC_DISK);
        $this->cleanupEmptyDirectory($this->temporaryDirectory((int) $product->getKey()), self::TEMP_DISK);
    }

    public function deleteTemporaryUpload(string $tempPath): void
    {
        $tempDisk = Storage::disk(self::TEMP_DISK);

        if ($tempDisk->exists($tempPath)) {
            $tempDisk->delete($tempPath);
        }

        $this->cleanupEmptyDirectory(dirname($tempPath), self::TEMP_DISK);
    }

    public function getRelativePath(ProductImage $image, string $size = 'large'): ?string
    {
        $baseFilename = $image->getRawOriginal('base_filename');

        if (filled($baseFilename)) {
            return $this->variantRelativePath((int) $image->product_id, $baseFilename, $size);
        }

        $legacyPath = $image->getRawOriginal('path');

        return filled($legacyPath) ? ltrim($legacyPath, '/') : null;
    }

    public function getImageUrl(ProductImage $image, string $size = 'large'): ?string
    {
        $relativePath = $this->getRelativePath($image, $size);

        if (! $relativePath) {
            return null;
        }

        return asset('storage/' . ltrim($relativePath, '/'));
    }

    public function getImageSrcSet(ProductImage $image): ?string
    {
        $relativePath = $this->getRelativePath($image, 'large');

        if (! $relativePath) {
            return null;
        }

        if (! filled($image->getRawOriginal('base_filename'))) {
            $url = $this->getImageUrl($image, 'large');

            return $url ? "{$url} 1000w" : null;
        }

        return collect(self::VARIANTS)
            ->map(fn (int $width, string $variant) => $this->getImageUrl($image, $variant) . " {$width}w")
            ->implode(', ');
    }

    public function getVariantWidths(): array
    {
        return self::VARIANTS;
    }

    private function shouldProcessSynchronously(): bool
    {
        return app()->environment(['local', 'testing']) || config('queue.default') === 'sync';
    }

    /**
     * @param  iterable<mixed>  $files
     * @return Collection<int, UploadedFile>
     */
    private function normalizeFiles(iterable $files): Collection
    {
        return collect($files)
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->values();
    }

    private function assertSupportedImage(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            throw new RuntimeException("Unsupported image extension [{$extension}].");
        }

        if (($file->getSize() ?? 0) > 2 * 1024 * 1024) {
            throw new RuntimeException("Image [{$file->getClientOriginalName()}] exceeds the 2 MB limit.");
        }
    }

    private function imageManager(): ImageManager
    {
        return extension_loaded('imagick')
            ? ImageManager::imagick()
            : ImageManager::gd();
    }

    private function buildTemporaryFilename(int $productId, string $originalName, ?string $extension = null): string
    {
        $slug = $this->slugFromOriginalName($originalName);
        $extension = strtolower($extension ?: pathinfo($originalName, PATHINFO_EXTENSION) ?: 'jpg');
        $timestamp = now()->timestamp;
        $counter = 0;

        do {
            $suffix = $counter === 0 ? '' : '-' . $counter;
            $filename = "{$slug}-{$timestamp}{$suffix}.{$extension}";
            $path = $this->temporaryDirectory($productId) . '/' . $filename;
            $counter++;
        } while (Storage::disk(self::TEMP_DISK)->exists($path));

        return $filename;
    }

    private function buildFinalFilename(int $productId, string $originalName): string
    {
        $slug = $this->slugFromOriginalName($originalName);
        $timestamp = now()->timestamp;
        $counter = 0;

        do {
            $suffix = $counter === 0 ? '' : '-' . $counter;
            $filename = "{$slug}-{$timestamp}{$suffix}.webp";
            $counter++;
        } while (
            ProductImage::query()
                ->where('product_id', $productId)
                ->where('base_filename', $filename)
                ->exists()
            || Storage::disk(self::PUBLIC_DISK)->exists($this->variantRelativePath($productId, $filename, 'large'))
        );

        return $filename;
    }

    private function slugFromOriginalName(string $originalName): string
    {
        return Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'product-image';
    }

    private function resolveAltText(string $originalName, ?string $altText): string
    {
        $altText = trim((string) $altText);

        if ($altText !== '') {
            return $altText;
        }

        return Str::headline(pathinfo($originalName, PATHINFO_FILENAME));
    }

    private function temporaryDirectory(int $productId): string
    {
        return self::TEMP_ROOT . '/' . $productId;
    }

    private function productDirectory(int $productId): string
    {
        return self::PRODUCT_ROOT . '/' . $productId;
    }

    private function variantRelativePath(int $productId, string $baseFilename, string $variant): string
    {
        $prefix = match ($variant) {
            'thumb' => 'thumb_',
            'medium' => 'medium_',
            'large' => 'large_',
            default => throw new RuntimeException("Unsupported image variant [{$variant}]."),
        };

        return $this->productDirectory($productId) . '/' . $prefix . $baseFilename;
    }

    /**
     * @return array<int, string>
     */
    private function allRelativePaths(ProductImage $image): array
    {
        $baseFilename = $image->getRawOriginal('base_filename');
        $paths = [];

        if (filled($baseFilename)) {
            foreach (array_keys(self::VARIANTS) as $variant) {
                $paths[] = $this->variantRelativePath((int) $image->product_id, $baseFilename, $variant);
            }
        }

        $legacyPath = $image->getRawOriginal('path');

        if (filled($legacyPath)) {
            $paths[] = ltrim($legacyPath, '/');
        }

        return array_values(array_unique(array_filter($paths)));
    }

    /**
     * @param  array<int|string, string>  $paths
     */
    private function deletePaths(array $paths): void
    {
        $paths = array_values(array_unique(array_filter($paths)));

        if ($paths === []) {
            return;
        }

        Storage::disk(self::PUBLIC_DISK)->delete($paths);
    }

    private function cleanupEmptyDirectory(string $directory, string $disk): void
    {
        if ($directory === '.' || $directory === '') {
            return;
        }

        $filesystem = Storage::disk($disk);

        if (! $filesystem->exists($directory)) {
            return;
        }

        if ($filesystem->files($directory) !== [] || $filesystem->directories($directory) !== []) {
            return;
        }

        $filesystem->deleteDirectory($directory);
    }
}

