<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
<<<<<<< HEAD
        'user_id',
        'customer_name',
        'email',
        'phone',
        'city',
        'country',
        'pincode',
=======
        'customer_name',
        'email',
        'phone',
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        'message',
        'desired_price',
        'status',
    ];

    protected $casts = [
        'desired_price' => 'decimal:2',
        'status' => 'string',
        'deleted_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
<<<<<<< HEAD

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
=======
}

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
