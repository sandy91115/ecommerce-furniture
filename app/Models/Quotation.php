<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'email',
        'phone',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
