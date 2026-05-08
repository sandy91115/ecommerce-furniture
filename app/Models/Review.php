<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
<<<<<<< HEAD
        'reviewer_name',
        'reviewer_email',
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        'rating',
        'title',
        'comment',
        'status',
        'images',
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'string', // pending/approved/rejected
        'images' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}

