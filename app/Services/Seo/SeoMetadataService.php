<?php

namespace App\Services\Seo;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SeoMetadataService
{
    public function syncForModel(Model $model, array $input, array $fallback = []): SeoMetadata
    {
        $payload = $this->payload($input, $fallback);

        return SeoMetadata::query()->updateOrCreate(
            ['seoable_type' => $model::class, 'seoable_id' => $model->getKey()],
            $payload
        );
    }

    public function syncForPage(string $pageKey, array $input, array $fallback = []): SeoMetadata
    {
        $payload = $this->payload($input, $fallback);

        return SeoMetadata::query()->updateOrCreate(
            ['page_key' => $pageKey],
            $payload
        );
    }

    public function payload(array $input, array $fallback = []): array
    {
        $input = array_replace($fallback, $input);
        $secondaryKeywords = $input['secondary_keywords'] ?? $input['secondary_keywords_text'] ?? null;

        if (is_string($secondaryKeywords)) {
            $secondaryKeywords = collect(preg_split('/[\r\n,]+/', $secondaryKeywords) ?: [])
                ->map(fn ($keyword) => trim((string) $keyword))
                ->filter()
                ->unique(fn ($keyword) => Str::lower($keyword))
                ->values()
                ->all();
        }

        $schemaData = $input['schema_data'] ?? null;
        if (is_string($schemaData)) {
            $schemaData = trim($schemaData);
            $schemaData = $schemaData !== '' ? json_decode($schemaData, true) : null;
            if (json_last_error() !== JSON_ERROR_NONE) {
                $schemaData = null;
            }
        }

        $payload = [
            'title' => $this->clean($input['title'] ?? null),
            'description' => $this->clean($input['description'] ?? null),
            'keywords' => $this->clean($input['keywords'] ?? null),
            'focus_keyword' => $this->clean($input['focus_keyword'] ?? null),
            'secondary_keywords' => $secondaryKeywords ?: null,
            'canonical_url' => $this->clean($input['canonical_url'] ?? null),
            'robots_index' => $this->bool($input, 'robots_index', true),
            'robots_follow' => $this->bool($input, 'robots_follow', true),
            'noindex_reason' => $this->clean($input['noindex_reason'] ?? null),
            'og_title' => $this->clean($input['og_title'] ?? null),
            'og_description' => $this->clean($input['og_description'] ?? null),
            'og_image' => $this->clean($input['og_image'] ?? null),
            'og_image_alt' => $this->clean($input['og_image_alt'] ?? null),
            'twitter_title' => $this->clean($input['twitter_title'] ?? null),
            'twitter_description' => $this->clean($input['twitter_description'] ?? null),
            'twitter_image' => $this->clean($input['twitter_image'] ?? null),
            'schema_type' => $this->clean($input['schema_type'] ?? null),
            'schema_data' => is_array($schemaData) ? $schemaData : null,
            'sitemap_priority' => $this->decimal($input['sitemap_priority'] ?? null),
            'sitemap_changefreq' => $this->clean($input['sitemap_changefreq'] ?? null),
        ];

        return Arr::where($payload, fn ($value) => $value !== '');
    }

    private function bool(array $input, string $key, bool $default): bool
    {
        if (! array_key_exists($key, $input)) {
            return $default;
        }

        return filter_var($input[$key], FILTER_VALIDATE_BOOLEAN);
    }

    private function clean(mixed $value): ?string
    {
        if (is_array($value) || is_object($value)) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : preg_replace('/\s+/', ' ', $value);
    }

    private function decimal(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return min(1.0, max(0.1, round((float) $value, 1)));
    }
}
