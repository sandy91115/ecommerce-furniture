@php
    $storyHighlights = [
        ['title' => 'Design Solutions', 'icon' => 'design'],
        ['title' => 'Expertise', 'icon' => 'expertise'],
        ['title' => 'Sustainable', 'icon' => 'sustainable'],
        ['title' => 'Quality Assurance', 'icon' => 'quality'],
        ['title' => 'Collaborative Approach', 'icon' => 'collaborative'],
    ];
@endphp

<div class="mt-8 border-t border-black/10 pt-8 dark:border-white/10" data-aos="fade-up" data-aos-delay="100">
    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 xl:grid-cols-5">
        @foreach ($storyHighlights as $item)
            <div class="flex flex-col items-center text-center">
                <div class="flex h-16 w-16 items-center justify-center text-primary">
                    @switch($item['icon'])
                        @case('design')
                            <svg viewBox="0 0 56 56" class="h-14 w-14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="28" cy="28" r="15.5" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M28 7.5V12.5M28 43.5V48.5M48.5 28H43.5M12.5 28H7.5M42.5 13.5L39 17M17 39L13.5 42.5M42.5 42.5L39 39M17 17L13.5 13.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <path d="M22.5 24.5C22.5 21.5 24.96 19 28 19C31.04 19 33.5 21.5 33.5 24.5C33.5 26.45 32.61 27.89 31.21 29.32C30.2 30.36 29.5 31.31 29.35 32.5H26.65C26.5 31.31 25.8 30.36 24.79 29.32C23.39 27.89 22.5 26.45 22.5 24.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M25.5 36H30.5M26.2 39H29.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            @break

                        @case('expertise')
                            <svg viewBox="0 0 56 56" class="h-14 w-14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="28" cy="30" r="11.5" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M23.5 30.2L26.7 33.4L32.9 27.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M28 11.5L29.58 15.03L33.42 15.38L30.54 17.92L31.38 21.68L28 19.7L24.62 21.68L25.46 17.92L22.58 15.38L26.42 15.03L28 11.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                <path d="M16 22L17.1 24.35L19.7 24.58L17.75 26.3L18.32 28.85L16 27.48L13.68 28.85L14.25 26.3L12.3 24.58L14.9 24.35L16 22Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                <path d="M40 22L41.1 24.35L43.7 24.58L41.75 26.3L42.32 28.85L40 27.48L37.68 28.85L38.25 26.3L36.3 24.58L38.9 24.35L40 22Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                            </svg>
                            @break

                        @case('sustainable')
                            <svg viewBox="0 0 56 56" class="h-14 w-14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.5 17.5C21.25 14.75 24.93 13 29 13C35.59 13 41 18.41 41 25H45L39.5 30.5L34 25H37C37 20.58 33.42 17 29 17C26.06 17 23.47 18.59 22.08 20.96" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M37.5 38.5C34.75 41.25 31.07 43 27 43C20.41 43 15 37.59 15 31H11L16.5 25.5L22 31H19C19 35.42 22.58 39 27 39C29.94 39 32.53 37.41 33.92 35.04" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M30.7 22.7C34.03 22.7 36.73 25.4 36.73 28.73C33.4 28.73 30.7 31.43 30.7 34.76C27.37 34.76 24.67 32.06 24.67 28.73C24.67 25.4 27.37 22.7 30.7 22.7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M28 35.5C28 31.85 30.25 29.6 33.9 29.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            @break

                        @case('quality')
                            <svg viewBox="0 0 56 56" class="h-14 w-14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="15" y="10.5" width="26" height="35" rx="4" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M23 10.5H33V15C33 16.1 32.1 17 31 17H25C23.9 17 23 16.1 23 15V10.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="M21.5 24H34.5M21.5 29H28.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <circle cx="35.5" cy="32.5" r="6.5" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M32.7 32.6L34.7 34.6L38.4 30.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21.5 35H28.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            @break

                        @case('collaborative')
                            <svg viewBox="0 0 56 56" class="h-14 w-14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="28" cy="14" r="7" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M28 21V27" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <path d="M20 27H36" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <path d="M16.5 43C19.54 43 22 40.54 22 37.5C22 34.46 19.54 32 16.5 32C13.46 32 11 34.46 11 37.5C11 40.54 13.46 43 16.5 43Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M39.5 43C42.54 43 45 40.54 45 37.5C45 34.46 42.54 32 39.5 32C36.46 32 34 34.46 34 37.5C34 40.54 36.46 43 39.5 43Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M28 48C31.04 48 33.5 45.54 33.5 42.5C33.5 39.46 31.04 37 28 37C24.96 37 22.5 39.46 22.5 42.5C22.5 45.54 24.96 48 28 48Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M22 38.5L24.1 40.2M34 38.5L31.9 40.2M28 27L28 37" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                            @break
                    @endswitch
                </div>

                <h4 class="mt-3 max-w-[140px] text-base font-medium leading-tight text-title dark:text-white sm:text-lg">
                    {{ $item['title'] }}
                </h4>
            </div>
        @endforeach
    </div>
</div>
