<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_blog');
    }

public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id')->where('approved', true)->with('user', 'children');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });

        static::updating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', Carbon::now())
                     ->latest('published_at');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function seoMetadata(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 150);
    }

    public function getTagsAttribute($value = null): array
    {
        if ($this->relationLoaded('categories')) {
            return $this->relations['categories']->pluck('name')->values()->all();
        }

        return $this->categories()->pluck('name')->all();
    }

    public function getImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . ltrim($this->featured_image, '/')) : asset('assets/img/shortcode/blog/blog-01.jpg');
    }

    public function deleteFiles(): void
    {
        if ($this->featured_image) {
            Storage::disk('public')->delete($this->featured_image);
        }
    }
}

