<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case RETURNED = 'returned';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
            self::RETURNED => 'Returned',
            self::REFUNDED => 'Refunded',
        };
    }

    public function badge(): string
    {
        return match($this) {
            self::PENDING, self::PROCESSING => 'bg-yellow-100 text-yellow-800',
            self::SHIPPED => 'bg-blue-100 text-blue-800',
            self::DELIVERED => 'bg-green-100 text-green-800',
            self::CANCELLED, self::RETURNED, self::REFUNDED => 'bg-red-100 text-red-800',
        };
    }
<<<<<<< HEAD

    public function color(): string
    {
        return match($this) {
            self::PENDING, self::PROCESSING => '#EC991D',
            self::SHIPPED => '#007BFF',
            self::DELIVERED => '#31A051',
            self::CANCELLED, self::RETURNED, self::REFUNDED => '#E13939',
        };
    }
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
}

