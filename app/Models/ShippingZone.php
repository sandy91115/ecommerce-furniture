<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingRate;

class ShippingZone extends Model
{
    protected $fillable = [
        'name',
        'description',
        'country_codes',
        'status',
    ];

    protected $casts = [
        'country_codes' => 'array',
        'status' => 'boolean',
    ];

    public function rates()
    {
        return $this->hasMany(ShippingRate::class);
    }
}

