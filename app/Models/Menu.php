<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_type',
        'parent_id',
        'title',
        'icon',
        'url',
        'order',
        'status'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeType(Builder $query, string $type)
    {
        return $query->where('menu_type', $type);
    }

    public static function getMenus(string $type)
    {
        return self::type($type)
            ->active()
            ->orderBy('order')
            ->orderBy('id')
            ->with('children')
            ->get()
            ->where('parent_id', null);
    }
}

