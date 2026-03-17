<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;

class Order extends Model
{
    use HasFactory;

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
        'items' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
}


