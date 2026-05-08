<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    protected $table = 'seo_metadata';

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'page_key',
        'title',
        'description',
        'keywords',
        'focus_keyword',
        'secondary_keywords',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'noindex_reason',
        'og_title',
        'og_description',
        'og_image',
        'og_image_alt',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_type',
        'schema_data',
        'score',
        'sitemap_priority',
        'sitemap_changefreq',
        'last_audited_at',
    ];

    protected $casts = [
        'secondary_keywords' => 'array',
        'schema_data' => 'array',
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
        'score' => 'integer',
        'sitemap_priority' => 'decimal:1',
        'last_audited_at' => 'datetime',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
