<?php

namespace App\Services\Seo;

class SeoData
{
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public ?string $keywords = null,
        public string $robots = 'index,follow',
        public string $type = 'website',
        public ?string $image = null,
        public ?string $ogTitle = null,
        public ?string $ogDescription = null,
        public ?string $twitterTitle = null,
        public ?string $twitterDescription = null,
        public ?string $twitterImage = null,
        public ?string $imageAlt = null,
        public ?string $siteName = null,
        public array $schemas = [],
    ) {
    }
}
