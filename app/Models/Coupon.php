<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type', // fixed/percentage
        'value',
        'max_uses',
        'used_count',
        'min_order_amount',
        'max_discount_amount',
        'valid_from',
        'valid_until',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'status' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(): bool
    {
        return $this->status 
            && $this->used_count < $this->max_uses 
            && now()->between($this->valid_from, $this->valid_until);
    }
}

