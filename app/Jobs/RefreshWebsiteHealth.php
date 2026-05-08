<?php

namespace App\Jobs;

use App\Services\Seo\SeoAuditService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class RefreshWebsiteHealth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 120;

    public function __construct(
        public ?string $homeUrl = null,
    ) {}

    public function handle(SeoAuditService $auditService): void
    {
        Cache::put('seo.website_health.status', [
            'state' => 'running',
            'started_at' => now()->toDateTimeString(),
        ], now()->addMinutes(15));

        try {
            $health = $auditService->health($this->homeUrl);
            $health['cached_at'] = now()->toDateTimeString();

            Cache::put('seo.website_health.result', $health, now()->addHour());
            Cache::put('seo.website_health.status', [
                'state' => 'completed',
                'finished_at' => now()->toDateTimeString(),
            ], now()->addHour());
        } finally {
            Cache::forget('seo.website_health.running');
        }
    }

    public function failed(Throwable $exception): void
    {
        Cache::forget('seo.website_health.running');
        Cache::put('seo.website_health.status', [
            'state' => 'failed',
            'error' => $exception->getMessage(),
            'finished_at' => now()->toDateTimeString(),
        ], now()->addHour());

        Log::error('Website health refresh failed.', [
            'error' => $exception->getMessage(),
        ]);
    }
}
