<?php

namespace App\Jobs;

use App\Services\ImageUploadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessProductImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public int $productId,
        public string $tempPath,
        public string $originalName,
        public ?string $altText = null,
        public bool $isFeatured = false,
    ) {
        $this->afterCommit();
    }

    public function backoff(): array
    {
        return [10, 60, 180];
    }

    public function handle(ImageUploadService $imageService): void
    {
        $processedImage = $imageService->processQueuedProductImage(
            productId: $this->productId,
            tempPath: $this->tempPath,
            originalName: $this->originalName,
            altText: $this->altText,
            isFeatured: $this->isFeatured,
        );

        if (! $processedImage) {
            Log::warning('Skipped product image processing because the product no longer exists.', [
                'product_id' => $this->productId,
                'temp_path' => $this->tempPath,
            ]);

            return;
        }

        Log::info('Product image processed successfully.', [
            'product_id' => $this->productId,
            'product_image_id' => $processedImage->id,
            'base_filename' => $processedImage->base_filename,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $tempPath = isset($this->tempPath) ? $this->tempPath : null;
        $productId = isset($this->productId) ? $this->productId : null;
        $originalName = isset($this->originalName) ? $this->originalName : null;

        if (is_string($tempPath) && $tempPath !== '') {
            app(ImageUploadService::class)->deleteTemporaryUpload($tempPath);
        }

        Log::error('Product image processing failed.', [
            'product_id' => $productId,
            'temp_path' => $tempPath,
            'original_name' => $originalName,
            'error' => $exception->getMessage(),
        ]);
    }
}

