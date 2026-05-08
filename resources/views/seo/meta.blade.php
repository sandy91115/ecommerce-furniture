@php
    $seo = $seo ?? app(\App\Services\Seo\SeoManager::class)->forCurrentPage(
        trim($__env->yieldContent('title')) ?: null,
        trim($__env->yieldContent('meta_description')) ?: null
    );
@endphp

<title>{{ $seo->title }}</title>
<meta name="description" content="{{ $seo->description }}">
@if($seo->keywords)
    <meta name="keywords" content="{{ $seo->keywords }}">
@endif
<meta name="robots" content="{{ $seo->robots }}">
<link rel="canonical" href="{{ $seo->canonical }}">

<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:site_name" content="{{ $seo->siteName ?: config('app.name') }}">
<meta property="og:title" content="{{ $seo->ogTitle ?: $seo->title }}">
<meta property="og:description" content="{{ $seo->ogDescription ?: $seo->description }}">
<meta property="og:url" content="{{ $seo->canonical }}">
@if($seo->image)
    <meta property="og:image" content="{{ $seo->image }}">
    @if($seo->imageAlt)
        <meta property="og:image:alt" content="{{ $seo->imageAlt }}">
    @endif
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo->twitterTitle ?: $seo->ogTitle ?: $seo->title }}">
<meta name="twitter:description" content="{{ $seo->twitterDescription ?: $seo->ogDescription ?: $seo->description }}">
@if($seo->twitterImage ?: $seo->image)
    <meta name="twitter:image" content="{{ $seo->twitterImage ?: $seo->image }}">
    @if($seo->imageAlt)
        <meta name="twitter:image:alt" content="{{ $seo->imageAlt }}">
    @endif
@endif

@foreach($seo->schemas as $schema)
    <script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
    </script>
@endforeach
