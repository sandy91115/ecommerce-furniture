<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'meta_title',
        'meta_description',
        'sort_order',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = \Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title')) {
                $page->slug = \Str::slug($page->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function getStatusBadgeAttribute()
    {
        $status = $this->status;
        $class = $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        return "<span class=\"px-2 py-1 rounded-full text-xs font-medium {$class}\">{$status}</span>";
    }
}

