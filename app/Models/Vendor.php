<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_name',
        'store_slug',
        'store_logo',
        'store_banner',
        'store_description',
        'store_address',
        'store_phone',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deleteFiles(): void
    {
        if ($this->store_logo) {
            Storage::disk('public')->delete($this->store_logo);
        }

        if ($this->store_banner) {
            Storage::disk('public')->delete($this->store_banner);
        }
    }
}
