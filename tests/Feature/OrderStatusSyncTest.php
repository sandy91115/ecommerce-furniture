<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;

it('moves a pending order to processing when its payment is marked paid', function () {
    $user = User::factory()->create();

    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'ORD-TEST-STATUS-SYNC',
        'total_amount' => 1200,
        'status' => OrderStatus::PENDING->value,
        'payment_status' => 'pending',
        'shipping_address' => 'Test Address',
        'items' => [],
    ]);

    app(OrderService::class)->update($order->id, [
        'status' => OrderStatus::PENDING->value,
        'payment_status' => 'paid',
        'shipping_address' => $order->shipping_address,
    ]);

    $freshOrder = $user->orders()->whereKey($order->id)->first();

    expect($freshOrder->status)->toBe(OrderStatus::PROCESSING)
        ->and($freshOrder->payment_status)->toBe('paid');
});
