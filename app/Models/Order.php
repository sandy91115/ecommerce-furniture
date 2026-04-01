<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'shipping_address',
        'items',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'status' => OrderStatus::class,
        'deleted_at' => 'datetime',
    ];

    protected function items(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeItemsPayload($value),
            set: fn ($value) => json_encode($this->normalizeItemsPayload($value), JSON_UNESCAPED_UNICODE),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    protected function normalizeItemsPayload(mixed $value): array
    {
        if ($value instanceof Collection) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        if (is_array($decoded)) {
            return $decoded;
        }

        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }
}
