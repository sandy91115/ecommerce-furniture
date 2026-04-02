<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'path',
        'alt',
        'featured',
        'base_filename',
        'original_name',
        'temp_path',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function getPathAttribute(?string $value): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getRelativePath($this, 'large') ?? $value;
    }

    public function getThumbPathAttribute(): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getRelativePath($this, 'thumb');
    }

    public function getMediumPathAttribute(): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getRelativePath($this, 'medium');
    }

    public function getLargePathAttribute(): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getRelativePath($this, 'large');
    }

    public function getThumbUrlAttribute(): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getImageUrl($this, 'thumb');
    }

    public function getMediumUrlAttribute(): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getImageUrl($this, 'medium');
    }

    public function getLargeUrlAttribute(): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getImageUrl($this, 'large');
    }

    public function getSrcsetAttribute(): ?string
    {
        return app(\App\Services\ImageUploadService::class)->getImageSrcSet($this);
    }

    public function getIsFeaturedAttribute(): bool
    {
        return (bool) ($this->attributes['featured'] ?? false);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

