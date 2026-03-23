<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'price',
        'sale_price',
        'stock',
        'short_description',
        'description',
        'status',
        'product_type',
        'extra_title',
        'extra_description',
        'featured',
        'dimensions',
        'weight',
        'material_id',
        'color_id',
        'warranty_months',
        'assembly_required',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'dimensions' => 'json',
        'weight' => 'decimal:2',
        'warranty_months' => 'integer',
        'assembly_required' => 'boolean',
        'featured' => 'boolean',
        'seo_title' => 'string',
        'seo_description' => 'string',
        'product_type' => 'string',
        'deleted_at' => 'datetime',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function attributeMaps(): HasMany
    {
        return $this->hasMany(ProductAttributeMap::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')->withPivot('quantity', 'price');
    }

    public function attributes(): HasMany
    {
        return $this->hasManyThrough(Attribute::class, ProductAttributeMap::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function deleteFiles(): void
    {
        foreach ($this->images as $image) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $this->images()->delete();
    }
}
