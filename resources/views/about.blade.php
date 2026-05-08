<!-- resources/views/about.blade.php -->
@extends('layouts.main')

@section('title', 'About Page')

@section('content')
@php
    $aboutDefaults = [
        'title' => 'About Us',
        'story_title' => 'Our Story Journey',
        'story_paragraphs' => [
            'We are a design-focused manufacturing company specializing in custom solid wood and epoxy resin furniture, Our collection is meticulously crafted to embody elegance and sustainability.',
            'Every piece we create is handcrafted using carefully sourced solid hardwoods and high-grade epoxy resins, ensuring durability, precision, and timeless appeal.',
        ],
        'story_highlights' => [
            ['title' => 'Design Solutions', 'icon' => 'design'],
            ['title' => 'Expertise', 'icon' => 'expertise'],
            ['title' => 'Sustainable', 'icon' => 'sustainable'],
            ['title' => 'Quality Assurance', 'icon' => 'quality'],
            ['title' => 'Collaborative Approach', 'icon' => 'collaborative'],
        ],
        'profiles' => [
            [
                'img' => 'assets/img/about/16.png',
                'name' => 'Prabhat Srivastava',
                'title' => 'Founder & Director',
                'desc' => 'With three decades of mastery in the timber industry, Prabhat Srivastava leads Carom Studios with a singular vision: to elevate Indian manufacturing to global excellence. His expertise in materials and production drives our uncompromising commitment to quality and craftsmanship.',
            ],
            [
                'img' => 'assets/img/about/15.png',
                'name' => 'Rishab Srivastava',
                'title' => 'Co-Founder & Creative Director',
                'desc' => 'Rishab Srivastava brings creative vision to Carom Studios. As our Creative Director, he is the driving force behind every piece of furniture we create, translating his passion for design into distinctive, functional pieces.',
            ],
        ],
        'what_we_do_title' => 'What We Do',
        'what_we_do_description' => 'Discover our premium furniture collections crafted with solid wood and epoxy resin for timeless elegance and durability.',
        'what_we_do' => [
            ['img' => 'assets/img/what/15.png', 'title' => 'Dinning Tables', 'desc' => 'Luxury solid wood dining tables for memorable gatherings'],
            ['img' => 'assets/img/what/16.png', 'title' => 'Center Tables', 'desc' => 'Elegant center pieces that elevate any living space'],
            ['img' => 'assets/img/what/17.png', 'title' => 'Doors', 'desc' => 'Premium solid wood doors with exquisite craftsmanship'],
            ['img' => 'assets/img/what/18.png', 'title' => 'T.V. Unit & Panels', 'desc' => 'Modern TV units and decorative panels for sophistication'],
        ],
        'how_we_do_title' => 'How We Do',
        'how_we_do' => [
            ['img' => 'assets/img/what/19.png', 'title' => 'Material Selection', 'desc' => 'Premium solid woods and high-grade epoxy resins carefully sourced'],
            ['img' => 'assets/img/what/20.png', 'title' => 'In-House Manufacturing', 'desc' => 'Precision craftsmanship in our dedicated workshops'],
            ['img' => 'assets/img/what/21.png', 'title' => 'Hand Finishing & Polishing', 'desc' => 'Artisan hand-finishing for flawless perfection'],
            ['img' => 'assets/img/what/22.png', 'title' => 'Quality Check', 'desc' => 'Rigorous inspection ensuring excellence in every piece'],
        ],
        'video_bg' => 'assets/img/about/video-bg.jpg',
        'video_url' => 'https://vimeo.com/360496931',
    ];

    $decodedAbout = isset($page) ? json_decode((string) $page->content, true) : [];
    $about = array_replace_recursive($aboutDefaults, is_array($decodedAbout) ? $decodedAbout : []);
    $storyHighlights = $about['story_highlights'];
    $teams = $about['profiles'];
    $aboutAsset = fn ($path) => \Illuminate\Support\Str::startsWith((string) $path, ['http://', 'https://', '//'])
        ? $path
        : (\Illuminate\Support\Str::startsWith((string) $path, ['assets/', 'storage/'])
            ? asset($path)
            : asset('storage/' . ltrim((string) $path, '/')));
@endphp

    <div class="about-page">



    <!-- Banner Start -->
    <div class="flex items-center gap-4 flex-wrap bg-overlay p-14 sm:p-16 before:bg-title before:bg-opacity-70"
        style="background-image:url('{{ asset('assets/img/shortcode/breadcumb.jpg') }}');">
        <div class="text-center w-full">
            <h2 class="text-white text-8 md:text-[40px] font-normal leading-none text-center">{{ $about['title'] }}</h2>
            <ul
                class="flex items-center justify-center gap-[10px] text-base md:text-lg leading-none font-normal text-white mt-3 md:mt-4 flex-wrap">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>/</li>
                <li class="text-primary">{{ $about['title'] }}</li>
            </ul>
        </div>
    </div>
    <!-- Banner End -->

    <!-- About Area Start -->
    <div class="s-pb-100 pt-12 md:pt-16" data-aos="fade-up">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto flex flex-col-reverse lg:grid lg:grid-cols-2">
                <!-- About Slider -->
                <div class="lg:bg-[#F8F8F9] lg:dark:bg-dark-secondary lg:pr-10 2xl:pr-0">
                    <div class="about-slider owl-carousel h-full" data-carousel-dots="true" data-carousel-margin="0">
                        <div><img class="object-cover w-full" src="{{ asset('assets/img/about/about-banner-01.jpg') }}"
                                alt="about"></div>
                        <div><img class="object-cover w-full" src="{{ asset('assets/img/about/about-banner-02.jpg') }}"
                                alt="about"></div>
                        <div><img class="object-cover w-full" src="{{ asset('assets/img/about/about-banner-03.jpg') }}"
                                alt="about"></div>
                    </div>
                </div>
                <!-- About Content -->
                <div
                    class="flex items-center py-8 sm:py-12 px-5 sm:px-12 md:px-8 lg:pr-12 lg:pl-16 2xl:pl-[160px] bg-[#F8F8F9] dark:bg-dark-secondary">
                    <div class="lg:max-w-[600px]">
                        <div>
                            <svg class="w-16" viewBox="0 0 68 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M66.9474 35.34H58.3624C60.294 31.9588 60.1455 27.7758 57.9789 24.5401C54.5907 19.309 56.176 17.3974 56.1818 17.3859C56.3998 17.1571 56.3912 16.7949 56.1624 16.5768C56.0566 16.4758 55.9161 16.4193 55.7697 16.4187H54.2301C54.3002 15.3447 54.4244 14.275 54.6021 13.2136C55.1068 13.389 55.636 13.4838 56.1703 13.4941C56.814 13.5248 57.4398 13.2767 57.8873 12.813C58.597 12.0232 60.1767 11.5023 60.749 11.365C61.0585 11.301 61.2576 10.9983 61.1935 10.6886C61.1296 10.3791 60.8268 10.18 60.5172 10.2441C60.5027 10.2471 60.4884 10.2506 60.4741 10.2547C60.3825 10.2547 58.1333 10.827 57.0401 12.0461C56.5823 12.5554 55.4891 12.3151 54.8137 12.0747C55.3174 9.64798 56.2331 6.86072 57.8929 5.01208C58.1047 4.77657 58.0855 4.41399 57.85 4.20223C57.6145 3.99047 57.2519 4.00964 57.0401 4.24515C56.3311 5.05701 55.7396 5.96444 55.2831 6.94084C54.1384 6.32845 52.9079 5.3841 53.1425 4.86327C53.5718 3.91892 54.4761 1.57236 53.6118 0.284608C53.451 0.0124633 53.1 -0.0776792 52.8279 0.0831464C52.5557 0.243972 52.4656 0.594955 52.6264 0.8671C52.6372 0.885271 52.649 0.902871 52.6616 0.919898C53.0222 1.49223 52.799 2.86583 52.0893 4.40541C51.3796 5.94498 53.6689 7.41016 54.8079 8.00538C53.816 10.7115 53.2373 13.5516 53.0909 16.4301H52.8848C52.877 15.6279 52.7888 14.8283 52.6216 14.0435C52.4908 13.4603 52.3207 12.8867 52.1122 12.3265C51.9848 11.9778 51.8398 11.6358 51.6772 11.302C51.56 10.9455 51.4833 10.5769 51.4483 10.2031C51.3338 9.38471 51.2251 8.60633 50.7901 8.17136C50.5675 7.94701 50.2052 7.94543 49.9807 8.16807C49.9795 8.16922 49.9784 8.17022 49.9774 8.17136C49.7555 8.39457 49.7555 8.75514 49.9774 8.97835C50.1728 9.41576 50.2872 9.88507 50.3151 10.3634C50.3584 10.8553 50.4702 11.3388 50.647 11.8C50.7844 12.0804 50.916 12.3723 51.0419 12.7214C51.1049 12.8988 51.1621 13.082 51.2193 13.2937C50.6718 13.2299 50.1841 12.9174 49.8973 12.4467C49.2422 11.5237 48.473 10.6871 47.6079 9.95704C47.3664 9.75315 47.0053 9.78362 46.8014 10.0251C46.8012 10.0253 46.8011 10.0256 46.8009 10.0257C46.597 10.2672 46.6275 10.6284 46.869 10.8323C46.8692 10.8324 46.8695 10.8326 46.8696 10.8327C47.6328 11.4895 48.3149 12.2349 48.9014 13.0534C49.4411 13.932 50.4059 14.459 51.4368 14.4384H51.5573C51.6865 15.0985 51.7555 15.7689 51.7634 16.4416H49.9777C49.7515 16.443 49.5473 16.5777 49.4569 16.785C49.3638 16.992 49.4021 17.2345 49.5541 17.4031C49.5541 17.4031 51.1567 19.3319 47.7685 24.563C45.6019 27.7987 45.4534 31.9817 47.385 35.3629H34.2614C34.6112 34.6687 34.8875 33.9398 35.0856 33.188H36.0528C37.3709 33.1882 38.4396 32.1198 38.4396 30.8016C38.4397 29.4835 37.3712 28.4148 36.0531 28.4148C36.0529 28.4148 36.0528 28.4148 36.0527 28.4148H35.4803C35.4338 27.8689 35.3554 27.3262 35.2457 26.7894C35.1904 26.5185 34.9496 26.3259 34.6733 26.3315H26.3859C26.1097 26.3259 25.8688 26.5185 25.8136 26.7894C25.6142 27.7399 25.5144 28.7085 25.516 29.6796C25.4901 31.6466 25.9232 33.5925 26.7809 35.3629H21.916V31.3566C21.916 31.0405 21.6598 30.7843 21.3437 30.7843H20.0731V29.5022H21.3437C21.6598 29.5022 21.916 29.246 21.916 28.9299V25.8851C21.916 25.569 21.6598 25.3127 21.3437 25.3127H3.93918C3.62311 25.3127 3.36685 25.569 3.36685 25.8851V28.3518H2.09627C1.78019 28.3518 1.52393 28.6081 1.52393 28.9242V31.3394C1.52393 31.6555 1.78019 31.9117 2.09627 31.9117H3.36685V35.3457H1.0489C0.732825 35.3457 0.476562 35.602 0.476562 35.9181V48.9272C0.476562 49.2433 0.732825 49.4995 1.0489 49.4995H1.52966V51.9262C1.52966 52.2423 1.78592 52.4986 2.10199 52.4986H8.88986L9.59956 62.6689C9.62059 62.9693 9.87084 63.2019 10.1719 63.2012H13.0336C13.3346 63.2019 13.5849 62.9693 13.6059 62.6689L14.3156 52.4986H53.6807L54.3904 62.6689C54.4114 62.9693 54.6616 63.2019 54.9627 63.2012H57.8244C58.1254 63.2019 58.3757 62.9693 58.3967 62.6689L59.1064 52.4986H65.8943C66.2103 52.4986 66.4666 52.2423 66.4666 51.9262V49.4995H66.9474C67.2634 49.4995 67.5197 49.2433 67.5197 48.9272V35.9124C67.5197 35.5963 67.2634 35.34 66.9474 35.34ZM35.5205 29.6568V29.5366H36.0528C36.7387 29.5364 37.2949 30.0924 37.2949 30.7784C37.2951 31.4643 36.739 32.0205 36.0531 32.0205C36.0529 32.0205 36.0528 32.0205 36.0527 32.0205H35.3201C35.4539 31.2397 35.521 30.449 35.5205 29.6568ZM48.7071 25.1582C51.294 21.1519 51.2425 18.7653 50.8705 17.5634H54.8768C54.5048 18.7653 54.4533 21.1633 57.0403 25.1582C59.1206 28.2251 59.1206 32.2503 57.0403 35.3171H48.7185C48.7185 35.3171 48.7128 35.3229 48.7071 35.3171C46.6566 32.2413 46.6566 28.2342 48.7071 25.1582ZM26.678 29.6568C26.6787 28.9184 26.7401 28.1816 26.8611 27.4533H34.1927C34.3166 28.1811 34.3779 28.9184 34.3759 29.6568C34.4228 31.6459 33.9282 33.6103 32.945 35.34H28.1088C27.1257 33.6103 26.6311 31.6459 26.678 29.6568ZM20.7715 31.9175V35.3515H4.51151V31.9175H20.7715ZM4.51151 26.4574H20.7715V28.3518H4.51151V26.4574ZM2.6686 30.7728V29.5022H3.7732C3.82614 29.523 3.88238 29.5346 3.93918 29.5366C3.99584 29.5334 4.05179 29.5218 4.10516 29.5022H18.9286V30.7728H2.6686ZM1.62123 48.3549V36.4961H33.4258V48.3549H1.62123ZM12.4956 62.0565H10.7042L10.0403 52.4986H13.1709L12.4956 62.0565ZM57.2864 62.0565H55.4892L54.8196 52.4986H57.9503L57.2864 62.0565ZM65.299 51.3539H2.67432V49.4995H65.3219L65.299 51.3539ZM66.3521 48.3549H34.5705V36.4847H66.375L66.3521 48.3549Z"
                                    fill="#BB976D"></path>
                                <path
                                    d="M60.0067 41.8481H56.1778C55.8617 41.8481 55.6055 42.1044 55.6055 42.4205C55.6055 42.7365 55.8617 42.9928 56.1778 42.9928H60.0067C60.3228 42.9928 60.579 42.7365 60.579 42.4205C60.579 42.1044 60.3228 41.8481 60.0067 41.8481Z"
                                    fill="#BB976D"></path>
                                <path
                                    d="M8.59566 41.8472H4.40046C4.08439 41.8472 3.82812 42.1034 3.82812 42.4195C3.82812 42.7356 4.08439 42.9918 4.40046 42.9918H8.59566C8.91173 42.9918 9.16799 42.7356 9.16799 42.4195C9.16799 42.1034 8.91173 41.8472 8.59566 41.8472Z"
                                    fill="#BB976D"></path>
                                <path
                                    d="M55.3901 27.1234C55.2373 26.8467 54.8892 26.7463 54.6125 26.8991C54.6085 26.9014 54.6043 26.9036 54.6003 26.9059C54.3193 27.0506 54.2087 27.3956 54.3532 27.6767C54.3535 27.6773 54.3539 27.678 54.3542 27.6786C54.3542 27.6786 55.207 29.3956 54.3142 32.429C54.2219 32.7313 54.3921 33.0512 54.6945 33.1434C54.6955 33.1437 54.6966 33.1439 54.6976 33.1444C54.7528 33.1499 54.8084 33.1499 54.8636 33.1444C55.1264 33.1548 55.3625 32.9848 55.4359 32.7323C56.4718 29.2239 55.4359 27.2093 55.3901 27.1234Z"
                                    fill="#BB976D"></path>
                            </svg>
                        </div>
                        <h3 class="font-medium leading-none mt-4 md:mt-6 text-2xl md:text-3xl">{{ $about['story_title'] }}</h3>
                        @foreach($about['story_paragraphs'] as $paragraph)
                            <p class="mt-3 text-base sm:text-lg">{{ $paragraph }}</p>
                        @endforeach

                        @include('includes.Pages.about-story-highlights')
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-12 md:mt-16">
            <div class="max-w-[1720px] mx-auto grid lg:grid-cols-2 gap-[30px]">
                @include('includes.Pages.teams')
            </div>
        </div>
    </div>

    <!-- About Area End -->

    <!-- What We Do & How We Do Start -->
    <section class="about-work-section bg-[#F9F8F3] dark:bg-dark-secondary s-py-50" data-aos="fade-up">
        <div class="container-fluid">
            <div class="max-w-[1720px] mx-auto text-center px-4">
                <h2 class="relative text-3xl md:text-[50px] font-normal uppercase mb-4 md:mb-6 pb-4"
                    style="font-family: serif;">
                    {{ $about['what_we_do_title'] }}
                    <span
                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#BB976D] to-transparent rounded-full"></span>
                </h2>
                <p class="text-xl text-gray-700 dark:text-gray-900 max-w-3xl mx-auto mb-12 px-4 leading-relaxed">{{ $about['what_we_do_description'] }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
                    @foreach($about['what_we_do'] as $item)
                        <div data-aos="fade-up" data-aos-delay="{{ ($loop->index + 1) * 100 }}"
                            class="group bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:scale-105 hover:shadow-2xl hover:shadow-[#BB976D]/25 hover:-translate-y-4 transition-all duration-500 flex flex-col items-center border-gradient hover:border-gold">
                            <div
                                class="w-full aspect-square overflow-hidden mb-6  border-4 border-gray-100 dark:border-gray-600 group-hover:border-[#BB976D]/50 transition-colors">
                                <img src="{{ $aboutAsset($item['img'] ?? '') }}" alt="{{ $item['title'] ?? '' }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <h4 class="text-lg md:text-xl font-semibold uppercase mb-3 group-hover:text-[#BB976D] transition-colors"
                                style="font-family: 'Josefin Sans', sans-serif;">{{ $item['title'] ?? '' }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">{{ $item['desc'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>

                <h2 class="relative text-3xl md:text-[50px] font-normal uppercase mb-4 md:mb-6 pb-4"
                    style="font-family: serif;">
                    {{ $about['how_we_do_title'] }}
                    <span
                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#BB976D] to-transparent rounded-full"></span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($about['how_we_do'] as $item)
                        <div data-aos="fade-up" data-aos-delay="{{ ($loop->index + 1) * 100 }}"
                            class="group bg-white dark:bg-gray-800 p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:scale-105 hover:shadow-2xl hover:shadow-[#BB976D]/25 hover:-translate-y-4 transition-all duration-500 flex flex-col items-center border-gradient hover:border-gold">
                            <div
                                class="w-full aspect-square overflow-hidden mb-6  border-4 border-gray-100 dark:border-gray-600 group-hover:border-[#BB976D]/50 transition-colors">
                                <img src="{{ $aboutAsset($item['img'] ?? '') }}" alt="{{ $item['title'] ?? '' }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <h4 class="text-lg md:text-xl font-semibold uppercase mb-3 group-hover:text-[#BB976D] transition-colors"
                                style="font-family: 'Josefin Sans', sans-serif;">{{ $item['title'] ?? '' }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center leading-relaxed">{{ $item['desc'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- What We Do & How We Do End -->

    <!-- Why Chose Start -->
    <div class="s-pb-100">
        <div class="container-fluid">
            <!-- Title -->
            <div class="max-w-xl mx-auto mb-8 md:mb-12 text-center" data-aos="fade-up">
                <div>
                    <svg class="mx-auto" width="63" height="63" viewBox="0 0 63 63" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M25.7294 12.8432L24.6844 18.9346C24.6552 19.1051 24.6744 19.2804 24.7396 19.4406C24.8048 19.6008 24.9136 19.7395 25.0535 19.8412C25.1935 19.9428 25.3591 20.0033 25.5316 20.0158C25.7041 20.0283 25.8767 19.9922 26.0298 19.9118L31.5002 17.0359L36.97 19.9118C37.1231 19.9922 37.2957 20.0283 37.4682 20.0158C37.6408 20.0033 37.8064 19.9428 37.9463 19.8412C38.0863 19.7395 38.195 19.6008 38.2602 19.4406C38.3255 19.2804 38.3446 19.1051 38.3155 18.9346L37.271 12.8432L41.6969 8.52966C41.8207 8.40881 41.9083 8.25572 41.9498 8.0877C41.9912 7.91968 41.9849 7.74341 41.9314 7.57882C41.8779 7.41423 41.7795 7.26786 41.6472 7.15627C41.5149 7.04468 41.3541 6.97231 41.1828 6.94733L35.0665 6.05918L32.3316 0.517299C32.2552 0.362046 32.1367 0.231305 31.9898 0.13988C31.8429 0.0484557 31.6732 0 31.5002 0C31.3271 0 31.1575 0.0484557 31.0106 0.13988C30.8636 0.231305 30.7452 0.362046 30.6687 0.517299L27.9333 6.05918L21.8175 6.94733C21.6463 6.9722 21.4854 7.04449 21.3531 7.15602C21.2208 7.26755 21.1223 7.41388 21.0688 7.57846C21.0154 7.74303 21.009 7.91929 21.0505 8.08729C21.0919 8.2553 21.1796 8.40835 21.3035 8.52913L25.7294 12.8432ZM28.6826 7.82434C28.8313 7.8026 28.9725 7.74509 29.094 7.65676C29.2156 7.56843 29.3139 7.45191 29.3805 7.31721L31.5002 3.02487L33.6199 7.31721C33.6865 7.45191 33.7848 7.56843 33.9063 7.65676C34.0279 7.74509 34.1691 7.8026 34.3178 7.82434L39.0573 8.51323L35.6277 11.8554C35.5203 11.9605 35.4401 12.0902 35.3939 12.2331C35.3478 12.3761 35.3371 12.5282 35.3628 12.6763L36.1719 17.3957L31.9326 15.1701C31.7995 15.1001 31.6514 15.0636 31.501 15.0636C31.3506 15.0636 31.2025 15.1001 31.0694 15.1701L26.83 17.3957L27.6397 12.6763C27.6653 12.5282 27.6545 12.3762 27.6084 12.2332C27.5622 12.0902 27.4821 11.9606 27.3748 11.8554L23.9457 8.51323L28.6826 7.82434Z"
                            fill="#BB976D" />
                        <path
                            d="M62.9526 9.72055C62.8991 9.55607 62.8007 9.40981 62.6684 9.29829C62.5362 9.18677 62.3755 9.11443 62.2043 9.08942L57.3471 8.38357L55.1744 3.98525C55.0977 3.83018 54.9792 3.69965 54.8322 3.60838C54.6853 3.51711 54.5157 3.46875 54.3427 3.46875C54.1697 3.46875 54.0002 3.51711 53.8532 3.60838C53.7062 3.69965 53.5877 3.83018 53.511 3.98525L51.3384 8.38622L46.4811 9.09207C46.3099 9.11702 46.1491 9.18937 46.0169 9.30094C45.8846 9.41251 45.7862 9.55884 45.7328 9.7234C45.6794 9.88796 45.6731 10.0642 45.7146 10.2322C45.7561 10.4001 45.8437 10.5531 45.9676 10.6739L49.482 14.0998L48.6527 18.9374C48.6236 19.1079 48.6427 19.2832 48.7079 19.4434C48.7732 19.6036 48.8819 19.7424 49.0219 19.844C49.1618 19.9457 49.3274 20.0061 49.5 20.0186C49.6725 20.0311 49.8451 19.9951 49.9982 19.9146L54.3435 17.6306L58.6888 19.9146C58.842 19.9951 59.0145 20.0311 59.1871 20.0186C59.3596 20.0061 59.5252 19.9457 59.6652 19.844C59.8051 19.7424 59.9139 19.6036 59.9791 19.4434C60.0443 19.2832 60.0634 19.1079 60.0343 18.9374L59.2045 14.0998L62.7194 10.6739C62.8436 10.5527 62.9313 10.3991 62.9725 10.2306C63.0137 10.0621 63.0068 9.88535 62.9526 9.72055ZM57.5601 13.112C57.4527 13.2171 57.3725 13.3468 57.3263 13.4898C57.2802 13.6327 57.2695 13.7848 57.2952 13.9329L57.8892 17.3985L54.7754 15.7616C54.6423 15.6917 54.4942 15.6551 54.3438 15.6551C54.1934 15.6551 54.0453 15.6917 53.9122 15.7616L50.7994 17.398L51.394 13.9323C51.4197 13.7843 51.409 13.6322 51.3628 13.4892C51.3167 13.3462 51.2364 13.2166 51.129 13.1115L48.6103 10.6569L52.0903 10.1514C52.2391 10.1297 52.3804 10.0722 52.502 9.98391C52.6237 9.89557 52.7221 9.77901 52.7887 9.64425L54.3451 6.4907L55.9015 9.64425C55.9681 9.77895 56.0664 9.89546 56.1879 9.9838C56.3095 10.0721 56.4507 10.1296 56.5994 10.1514L60.0799 10.6569L57.5601 13.112Z"
                            fill="#BB976D" />
                        <path
                            d="M13.4299 20.0176C13.565 20.0177 13.6984 19.9883 13.8209 19.9314C13.9434 19.8745 14.0519 19.7916 14.139 19.6884C14.2261 19.5851 14.2895 19.4641 14.325 19.3338C14.3604 19.2035 14.3669 19.067 14.344 18.9339L13.5147 14.0963L17.0296 10.6704C17.1536 10.5496 17.2412 10.3965 17.2827 10.2285C17.3242 10.0605 17.3178 9.88428 17.2643 9.71971C17.2108 9.55513 17.1124 9.4088 16.9801 9.29727C16.8477 9.18573 16.6869 9.11344 16.5156 9.08858L11.6584 8.38272L9.48573 3.9844C9.40912 3.82932 9.29065 3.69875 9.14373 3.60745C8.9968 3.51615 8.82727 3.46777 8.65429 3.46777C8.48131 3.46777 8.31177 3.51615 8.16485 3.60745C8.01792 3.69875 7.89946 3.82932 7.82285 3.9844L5.65018 8.38537L0.794542 9.08858C0.623248 9.11336 0.462309 9.18559 0.329933 9.29709C0.197556 9.4086 0.0990246 9.55492 0.045485 9.71951C-0.00805467 9.88409 -0.0144656 10.0604 0.0269773 10.2284C0.0684203 10.3965 0.156063 10.5496 0.279991 10.6704L3.79494 14.0963L2.96509 18.9339C2.93594 19.1044 2.95507 19.2797 3.0203 19.4399C3.08553 19.6001 3.19427 19.7389 3.33423 19.8405C3.47419 19.9422 3.63979 20.0026 3.81232 20.0151C3.98485 20.0276 4.15742 19.9916 4.31055 19.9111L8.65588 17.6271L13.0012 19.9111C13.1334 19.9807 13.2805 20.0173 13.4299 20.0176ZM9.08458 15.7608C8.95146 15.6908 8.80334 15.6543 8.65296 15.6543C8.50259 15.6543 8.35446 15.6908 8.22134 15.7608L5.11019 17.395L5.70476 13.9294C5.73031 13.7813 5.71954 13.6293 5.67339 13.4863C5.62724 13.3433 5.54709 13.2137 5.4398 13.1085L2.92163 10.654L6.40107 10.1484C6.54988 10.1268 6.69122 10.0694 6.81289 9.98106C6.93456 9.89271 7.03293 9.7761 7.09951 9.64128L8.65482 6.48721L10.2112 9.64075C10.2778 9.77557 10.3761 9.89218 10.4978 9.98053C10.6195 10.0689 10.7608 10.1263 10.9096 10.1479L14.3891 10.6534L11.8709 13.1112C11.7635 13.2163 11.6832 13.3459 11.637 13.4889C11.5909 13.6319 11.5802 13.784 11.6059 13.932L12.2 17.3977L9.08458 15.7608Z"
                            fill="#BB976D" />
                        <path
                            d="M50.9001 42.2896C50.8989 41.2945 50.5031 40.3404 49.7995 39.6368C49.0958 38.9331 48.1418 38.5373 47.1466 38.5362H35.7099V26.3322C35.7099 25.2159 35.2665 24.1452 34.4771 23.3559C33.6877 22.5665 32.6171 22.123 31.5008 22.123C30.3845 22.123 29.3139 22.5665 28.5245 23.3559C27.7351 24.1452 27.2917 25.2159 27.2917 26.3322V28.6585C27.2945 29.9931 26.869 31.2933 26.0776 32.368L21.5527 38.5362H13.0289C12.783 38.5362 12.5471 38.6339 12.3732 38.8078C12.1993 38.9817 12.1016 39.2176 12.1016 39.4636V62.0725C12.1016 62.3185 12.1993 62.5543 12.3732 62.7283C12.5471 62.9022 12.783 62.9999 13.0289 62.9999H45.5569C46.2926 63.0002 47.0122 62.7843 47.6262 62.3788C48.2401 61.9734 48.7213 61.3964 49.0099 60.7196C49.2984 60.0429 49.3817 59.2962 49.2492 58.5725C49.1167 57.8488 48.7744 57.18 48.2648 56.6493C48.7037 56.337 49.0702 55.9337 49.3392 55.467C49.6082 55.0002 49.7735 54.481 49.8238 53.9446C49.8741 53.4082 49.8082 52.8673 49.6306 52.3587C49.453 51.8501 49.1679 51.3857 48.7947 50.9972C49.2336 50.6849 49.6001 50.2816 49.8691 49.8149C50.1382 49.3481 50.3035 48.8289 50.3538 48.2925C50.404 47.7561 50.3381 47.2152 50.1605 46.7066C49.983 46.198 49.6978 45.7336 49.3246 45.3451C49.8118 44.9983 50.2089 44.54 50.483 44.0085C50.7571 43.477 50.9 42.8876 50.9001 42.2896ZM13.9563 40.3909H21.0953V61.1452H13.9563V40.3909ZM47.1466 44.1883H46.6167C46.3708 44.1883 46.1349 44.286 45.961 44.4599C45.7871 44.6338 45.6894 44.8697 45.6894 45.1157C45.6894 45.3616 45.7871 45.5975 45.961 45.7714C46.1349 45.9453 46.3708 46.043 46.6167 46.043C47.1149 46.0513 47.5898 46.2551 47.9391 46.6103C48.2885 46.9655 48.4842 47.4438 48.4842 47.942C48.4842 48.4402 48.2885 48.9185 47.9391 49.2737C47.5898 49.6289 47.1149 49.8326 46.6167 49.8409H46.0868C45.8409 49.8409 45.605 49.9387 45.4311 50.1126C45.2571 50.2865 45.1594 50.5224 45.1594 50.7683C45.1594 51.0143 45.2571 51.2501 45.4311 51.424C45.605 51.598 45.8409 51.6957 46.0868 51.6957C46.5904 51.6957 47.0733 51.8957 47.4294 52.2518C47.7855 52.6078 47.9855 53.0908 47.9855 53.5944C47.9855 54.0979 47.7855 54.5809 47.4294 54.9369C47.0733 55.293 46.5904 55.4931 46.0868 55.4931H45.5569C45.3109 55.4931 45.0751 55.5908 44.9011 55.7647C44.7272 55.9386 44.6295 56.1745 44.6295 56.4204C44.6295 56.6664 44.7272 56.9022 44.9011 57.0761C45.0751 57.2501 45.3109 57.3478 45.5569 57.3478C46.0605 57.3478 46.5434 57.5478 46.8995 57.9039C47.2555 58.26 47.4556 58.7429 47.4556 59.2465C47.4556 59.75 47.2555 60.233 46.8995 60.589C46.5434 60.9451 46.0605 61.1452 45.5569 61.1452H22.95V39.7667L27.5731 33.4633C28.5984 32.0712 29.1498 30.3869 29.1464 28.658V26.3317C29.1464 25.7072 29.3944 25.1084 29.836 24.6668C30.2775 24.2253 30.8764 23.9772 31.5008 23.9772C32.1252 23.9772 32.7241 24.2253 33.1656 24.6668C33.6072 25.1084 33.8552 25.7072 33.8552 26.3317V39.463C33.8552 39.709 33.9529 39.9449 34.1269 40.1188C34.3008 40.2927 34.5366 40.3904 34.7826 40.3904H47.1466C47.6502 40.3904 48.1331 40.5904 48.4892 40.9465C48.8453 41.3026 49.0453 41.7855 49.0453 42.2891C49.0453 42.7926 48.8453 43.2756 48.4892 43.6317C48.1331 43.9877 47.6502 44.1878 47.1466 44.1878V44.1883Z"
                            fill="#BB976D" />
                    </svg>
                </div>
                <h3 class="font-medium leading-none mt-4 md:mt-6 text-2xl md:text-3xl">Why You Choose Us</h3>
                <p class="mt-3">Choose us for exceptional quality, We prioritize your satisfaction by offering premium
                    products and a seamless shopping experience. </p>
            </div>
            <div class="max-w-sm sm:max-w-[1720px] mx-auto grid sm:grid-cols-2 md:grid-cols-3 xl:flex lg:justify-between gap-7 flex-wrap lg:flex-nowrap"
                data-aos="fade-up" data-aos-delay="100">

                <!-- includes/Pages/services3.blade.php -->
                @include('includes.Pages.services3')

            </div>
        </div>
    </div>
    <!-- Why Chose End -->

    <!-- Video Section Start -->
    <div class="container-fluid" data-aos="fade-up">
        <div class="bg-overlay before:bg-title before:bg-opacity-20 h-64 sm:h-96 lg:h-[650px] flex items-center justify-center max-w-[1720px] mx-auto"
            style="background-image: url('{{ $aboutAsset($about['video_bg']) }}');">
            <a href="{{ $about['video_url'] }}"
                class="popup-video w-12 sm:w-[70px] h-12 sm:h-[70px] rounded-full bg-white dark:bg-title flex items-center justify-center">
                <svg class="fill-current text-title dark:text-white" width="15" height="17" viewBox="0 0 15 17" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.58357 0.369445C1.15676 -0.497057 0 0.212792 0 1.95367V14.8006C0 16.5432 1.15676 17.2521 2.58357 16.3864L13.1895 9.94678C14.6168 9.07997 14.6168 7.67561 13.1895 6.80901L2.58357 0.369445Z" />
                </svg>
            </a>
        </div>
    </div>
    <!-- Video Section End -->

   

    @include('includes.footer')

    </div>

@endsection
