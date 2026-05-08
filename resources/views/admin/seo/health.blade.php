@extends('admin.layouts.app')

@section('title', 'Website Health')

@section('content')
@php
    $statusState = $health['refresh_status']['state'] ?? (!empty($health['is_running']) ? 'running' : (!empty($health['cached_at']) || !empty($health['checked_at']) ? 'completed' : 'pending'));
    $statusText = [
        'queued' => 'Queued',
        'running' => 'Scanning',
        'completed' => 'Ready',
        'failed' => 'Failed',
        'pending' => 'Pending',
    ][$statusState] ?? ucfirst($statusState);
    $statusClass = [
        'queued' => 'bg-blue-100 text-blue-700 border-blue-200',
        'running' => 'bg-amber-100 text-amber-700 border-amber-200',
        'completed' => 'bg-green-100 text-green-700 border-green-200',
        'failed' => 'bg-red-100 text-red-700 border-red-200',
        'pending' => 'bg-gray-100 text-gray-700 border-gray-200',
    ][$statusState] ?? 'bg-gray-100 text-gray-700 border-gray-200';
    $shouldPollHealthStatus = in_array($statusState, ['queued', 'running', 'pending'], true);
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Website Health</h1>
                <span id="health-status-badge" class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold {{ $statusClass }}">
                    @if($statusState === 'running')
                        <i class="fas fa-spinner fa-spin"></i>
                    @elseif($statusState === 'completed')
                        <i class="fas fa-check-circle"></i>
                    @elseif($statusState === 'failed')
                        <i class="fas fa-circle-exclamation"></i>
                    @else
                        <i class="fas fa-clock"></i>
                    @endif
                    <span id="health-status-text">{{ $statusText }}</span>
                </span>
            </div>
            <p class="text-sm text-gray-500">Cached background checks for response speed, metadata, schema, sitemap, robots, and PageSpeed Insights.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.seo.health.refresh') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    <i class="fas fa-rotate"></i>
                    {{ in_array($statusState, ['queued', 'running'], true) ? 'Scan queued' : 'Refresh scan' }}
                </button>
            </form>
            <a href="{{ route('admin.seo.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">
                <i class="fas fa-arrow-left"></i>
                SEO Dashboard
            </a>
        </div>
    </div>

    <div id="health-status-panel" class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
        @if($statusState === 'queued')
            Scan queued. Waiting for the queue worker to start it.
        @elseif($statusState === 'running')
            Health scan is running in the background. This page will refresh automatically when results are ready.
        @elseif($statusState === 'failed')
            Health scan failed: {{ $health['refresh_status']['error'] ?? 'Unknown error' }}
        @elseif(!empty($health['cached_at']))
            Result ready. Last scan: {{ $health['cached_at'] }}.
        @elseif(!empty($health['checked_at']))
            Result ready. Last scan: {{ $health['checked_at'] }}.
        @else
            Health scan has been queued. Run <span class="font-mono">php artisan queue:work</span> to process background jobs.
        @endif
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-500">Health Score</p>
            <p class="mt-2 text-3xl font-bold {{ ($health['score'] ?? 0) >= 90 ? 'text-green-600' : (($health['score'] ?? 0) >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                {{ $health['score'] ?? 'Pending' }}{{ isset($health['score']) ? '%' : '' }}
            </p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-500">Homepage URL</p>
            <p class="mt-2 break-all text-sm font-bold text-gray-900">{{ $health['url'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-500">Response Time</p>
            <p class="mt-2 text-3xl font-bold {{ ($health['response_ms'] ?? 999999) <= 1500 ? 'text-green-600' : 'text-amber-600' }}">{{ $health['response_ms'] ?? 'Pending' }}{{ isset($health['response_ms']) ? ' ms' : '' }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-500">HTML Size</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $health['size_kb'] ?? 'N/A' }} KB</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-500">PageSpeed Target</p>
            <p class="mt-2 break-all text-sm font-bold text-gray-900">{{ $health['pagespeed']['target_url'] ?? $health['pagespeed_url'] ?? $health['url'] }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">PageSpeed Insights</h2>
        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
            <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-700">
                API key: {{ ($health['pagespeed']['api_key_present'] ?? false) ? 'Detected' : 'Missing' }}
            </span>
            <span class="rounded-full bg-blue-100 px-3 py-1 text-blue-700">Strategy: Mobile + Desktop</span>
            @if(!empty($health['pagespeed']['ssl_retry']))
                <span class="rounded-full bg-amber-100 px-3 py-1 text-amber-700">SSL verification retried locally</span>
            @endif
        </div>
        @if(!($health['pagespeed']['enabled'] ?? false))
            <p class="mt-3 rounded-lg bg-amber-50 p-4 text-sm text-amber-800">{{ $health['pagespeed']['message'] }}</p>
        @elseif(!empty($health['pagespeed']['skipped']))
            <p class="mt-3 rounded-lg bg-amber-50 p-4 text-sm text-amber-800">{{ $health['pagespeed']['message'] }}</p>
        @elseif(!empty($health['pagespeed']['error']))
            <p class="mt-3 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $health['pagespeed']['error'] }}</p>
        @else
            @foreach(['mobile' => 'Mobile', 'desktop' => 'Desktop'] as $strategyKey => $strategyLabel)
                <div class="mt-6">
                    <h3 class="mb-3 text-base font-bold text-gray-900">{{ $strategyLabel }}</h3>

                    @if(!empty($health['pagespeed'][$strategyKey]['error']))
                        <p class="rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $health['pagespeed'][$strategyKey]['error'] }}</p>
                    @elseif(!empty($health['pagespeed'][$strategyKey]['scores']))
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            @foreach($health['pagespeed'][$strategyKey]['scores'] as $label => $score)
                                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                                    <p class="text-xs font-semibold uppercase text-gray-500">{{ str_replace('-', ' ', $label) }}</p>
                                    <p class="mt-2 text-3xl font-bold {{ $score >= 90 ? 'text-green-600' : ($score >= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ $score }}</p>
                                </div>
                            @endforeach
                        </div>

                        @if(!empty($health['pagespeed'][$strategyKey]['metrics']))
                            <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-4">
                                @foreach($health['pagespeed'][$strategyKey]['metrics'] as $label => $value)
                                    @if($value)
                                        <div class="rounded-lg border border-gray-100 p-3">
                                            <p class="text-xs font-semibold uppercase text-gray-500">{{ str_replace('-', ' ', $label) }}</p>
                                            <p class="mt-1 text-sm font-bold text-gray-900">{{ $value }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @else
                        <p class="rounded-lg bg-gray-50 p-4 text-sm text-gray-600">No {{ strtolower($strategyLabel) }} PageSpeed data available yet. Refresh scan again.</p>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Technical Checks</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($health['checks'] as $check)
                <div class="flex items-start justify-between gap-4 px-6 py-4">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $check['label'] }}</p>
                        @if($check['detail'])
                            <p class="mt-1 text-sm text-gray-500">{{ $check['detail'] }}</p>
                        @endif
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $check['passed'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $check['passed'] ? 'Passed' : 'Needs work' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (() => {
            const statusUrl = @json(route('admin.seo.health.status'));
            const shouldPoll = @json($shouldPollHealthStatus);

            if (!shouldPoll) {
                return;
            }

            const badge = document.getElementById('health-status-badge');
            const text = document.getElementById('health-status-text');
            const panel = document.getElementById('health-status-panel');

            const badgeClasses = {
                queued: 'bg-blue-100 text-blue-700 border-blue-200',
                running: 'bg-amber-100 text-amber-700 border-amber-200',
                completed: 'bg-green-100 text-green-700 border-green-200',
                failed: 'bg-red-100 text-red-700 border-red-200',
                pending: 'bg-gray-100 text-gray-700 border-gray-200',
            };

            const labels = {
                queued: 'Queued',
                running: 'Scanning',
                completed: 'Ready',
                failed: 'Failed',
                pending: 'Pending',
            };

            const icons = {
                queued: 'fas fa-clock',
                running: 'fas fa-spinner fa-spin',
                completed: 'fas fa-check-circle',
                failed: 'fas fa-circle-exclamation',
                pending: 'fas fa-clock',
            };

            const setStatus = (data) => {
                const state = data.state || (data.is_running ? 'running' : 'pending');

                if (badge) {
                    badge.className = `inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold ${badgeClasses[state] || badgeClasses.pending}`;
                    badge.querySelector('i')?.setAttribute('class', icons[state] || icons.pending);
                }

                if (text) {
                    text.textContent = labels[state] || state;
                }

                if (panel) {
                    if (state === 'completed') {
                        panel.textContent = `Result ready. Last scan: ${data.cached_at || data.finished_at || 'just now'}.`;
                    } else if (state === 'failed') {
                        panel.textContent = `Health scan failed: ${data.error || 'Unknown error'}`;
                    } else if (state === 'running') {
                        panel.textContent = 'Health scan is running in the background. This page will refresh automatically when results are ready.';
                    } else {
                        panel.textContent = 'Scan queued. Waiting for the queue worker to start it.';
                    }
                }

                if (state === 'completed' || state === 'failed') {
                    window.setTimeout(() => window.location.reload(), 1200);
                    return true;
                }

                return false;
            };

            const poll = async () => {
                try {
                    const response = await fetch(statusUrl, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    if (setStatus(await response.json())) {
                        window.clearInterval(timer);
                    }
                } catch (error) {
                    console.error('Unable to refresh website health status.', error);
                }
            };

            const timer = window.setInterval(poll, 5000);
            poll();
        })();
    </script>
@endpush
@endsection