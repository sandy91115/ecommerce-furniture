<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,returned,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_tracking' => 'nullable|string|max:100',
        ];
    }
}

