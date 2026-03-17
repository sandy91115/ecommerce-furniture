<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color_code',
    ];

    protected $casts = [
        'color_code' => 'string',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (AttributeValue $attributeValue) {
            $attributeValue->slug = Str::slug($attributeValue->value);
        });

        static::updating(function (AttributeValue $attributeValue) {
            $attributeValue->slug = Str::slug($attributeValue->value);
        });
    }
}
