<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'menu_type',
        'parent_id',
        'title',
        'icon',
        'url',
        'order',
        'status',
        'permission',
        'is_permanent'
    ];

    protected $casts = [
        'order' => 'integer',
<<<<<<< HEAD
        'is_permanent' => 'boolean',
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        'deleted_at' => 'datetime',
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
<<<<<<< HEAD
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->with([
                'children' => fn ($query) => $query
                    ->active()
                    ->orderBy('order')
                    ->orderBy('id'),
            ])
            ->get()
            ->unique(fn (Menu $menu) => ($menu->parent_id ?: 'root') . '|' . ($menu->url ?: '') . '|' . $menu->title)
            ->values();
=======
            ->orderBy('order')
            ->orderBy('id')
            ->with('children')
            ->get()
            ->where('parent_id', null);
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }
}
