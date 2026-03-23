<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
        'deleted_at' => 'datetime',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductAttributeMap::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            $attribute->slug = \Illuminate\Support\Str::slug($attribute->name);
        });

        static::updating(function ($attribute) {
            $attribute->slug = \Illuminate\Support\Str::slug($attribute->name);
        });
    }
}

