<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
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
            'items' => 'required|json',
        ];
    }
}

