<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $items = $this->input('items');

        if (is_string($items) && $items !== '') {
            $decoded = json_decode($items, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $items = is_string($decoded) ? json_decode($decoded, true) : $decoded;
            }
        }

        if (is_array($items)) {
            $items = array_values(array_map(function ($item) {
                if (! is_array($item)) {
                    return [];
                }

                return [
                    'product_id' => $item['product_id'] ?? $item['id'] ?? null,
                    'product_name' => $item['product_name'] ?? $item['name'] ?? null,
                    'product_sku' => $item['product_sku'] ?? $item['sku'] ?? null,
                    'quantity' => $item['quantity'] ?? $item['qty'] ?? null,
                    'price' => $item['price'] ?? null,
                    'image' => $item['image'] ?? null,
                    'attributes' => $item['attributes'] ?? null,
                    'variation_id' => $item['variation_id'] ?? null,
                ];
            }, $items));

            $totalAmount = array_reduce($items, function ($total, $item) {
                $quantity = (int) ($item['quantity'] ?? 0);
                $price = (float) ($item['price'] ?? 0);

                return $total + ($quantity * $price);
            }, 0);

            $this->merge([
                'items' => $items,
                'total_amount' => number_format($totalAmount, 2, '.', ''),
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,returned,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'shipping_address' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_name' => 'nullable|string|max:255',
            'items.*.product_sku' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.image' => 'nullable|string|max:2048',
            'items.*.attributes' => 'nullable|array',
            'items.*.variation_id' => 'nullable|exists:product_variations,id',
        ];
    }
}

