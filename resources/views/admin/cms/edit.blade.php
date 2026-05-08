@extends('admin.layouts.app')

<<<<<<< HEAD
@section('title', 'Edit Page')

@section('content')
@php
    $isAboutPage = $page->slug === 'about';
    $isContactPage = $page->slug === 'contact';
    $hasCmsPreview = in_array($page->slug, ['contact', 'terms-and-conditions', 'return-policy', 'privacy-policy'], true);
    $aboutDefaults = [
        'title' => 'About Us',
        'story_title' => 'Our Story Journey',
        'story_paragraphs' => [
            'We are a design-focused manufacturing company specializing in custom solid wood and epoxy resin furniture, Our collection is meticulously crafted to embody elegance and sustainability.',
            'Every piece we create is handcrafted using carefully sourced solid hardwoods and high-grade epoxy resins, ensuring durability, precision, and timeless appeal.',
        ],
        'profiles' => [
            ['img' => 'assets/img/about/16.png', 'name' => 'Prabhat Srivastava', 'title' => 'Founder & Director', 'desc' => 'With three decades of mastery in the timber industry, Prabhat Srivastava leads Carom Studios with a singular vision: to elevate Indian manufacturing to global excellence.'],
            ['img' => 'assets/img/about/15.png', 'name' => 'Rishab Srivastava', 'title' => 'Co-Founder & Creative Director', 'desc' => 'Rishab Srivastava brings creative vision to Carom Studios and every piece of furniture we create.'],
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
    $aboutDecoded = json_decode((string) old('content', $page->content), true);
    $aboutContent = array_replace_recursive($aboutDefaults, is_array($aboutDecoded) ? $aboutDecoded : []);
    $aboutAssetAdmin = fn ($path) => \Illuminate\Support\Str::startsWith((string) $path, ['http://', 'https://', '//'])
        ? $path
        : (\Illuminate\Support\Str::startsWith((string) $path, ['assets/', 'storage/'])
            ? asset($path)
            : asset('storage/' . ltrim((string) $path, '/')));
    $contactDecoded = json_decode((string) old('content', $page->content), true);
    $contactContent = is_array($contactDecoded) ? $contactDecoded : [
        'content' => (string) old('content', $page->content),
        'image' => 'assets/img/thumb/contact-thumb.jpg',
    ];
@endphp
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Page</h1>
=======
@section('title', 'Edit CMS Page')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit CMS Page</h1>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            <p class="mt-1 text-sm text-gray-500">Update "{{ $page->title }}"</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.cms.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                <i class="fas fa-arrow-left mr-2"></i>
<<<<<<< HEAD
                Back to Pages
=======
                Back to CMS
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            </a>
        </div>
    </div>

<<<<<<< HEAD
    <form action="{{ route('admin.cms.update', $page) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-xl rounded-lg overflow-hidden">
=======
    <form action="{{ route('admin.cms.update', $page) }}" method="POST" class="bg-white shadow-xl rounded-lg overflow-hidden">
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        @csrf
        @method('PUT')
        <div class="px-6 py-8">
            <!-- Same form as create, but with $page values -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">your-site.com/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" class="flex-1 px-4 py-3 border border-l-0 border-gray-300 rounded-r-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-300 @enderror">
                    </div>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror">
                        <option value="active" {{ old('status', $page->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $page->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $page->sort_order) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-300 @enderror" min="0">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
<<<<<<< HEAD
                @if($isAboutPage)
                    <input type="hidden" name="content" id="content" value="{{ e(json_encode($aboutContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}">
                    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_420px] gap-6" id="about-editor">
                        <div class="space-y-6">
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Story</h3>
                                <div class="grid gap-4">
                                    <input data-about-field="title" type="text" value="{{ $aboutContent['title'] }}" class="about-input w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Page title">
                                    <input data-about-field="story_title" type="text" value="{{ $aboutContent['story_title'] }}" class="about-input w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Story heading">
                                    <textarea data-about-field="story_paragraphs" rows="5" class="about-input w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="One paragraph per line">{{ implode("\n", $aboutContent['story_paragraphs'] ?? []) }}</textarea>
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profiles</h3>
                                <div class="grid gap-4">
                                    @foreach($aboutContent['profiles'] as $profile)
                                        <div class="rounded-lg border border-gray-200 bg-white p-4">
                                            <div class="grid gap-3 md:grid-cols-2">
                                                <input data-about-repeat="profiles" data-index="{{ $loop->index }}" data-key="name" type="text" value="{{ $profile['name'] ?? '' }}" class="about-input px-4 py-3 border border-gray-300 rounded-lg" placeholder="Name">
                                                <input data-about-repeat="profiles" data-index="{{ $loop->index }}" data-key="title" type="text" value="{{ $profile['title'] ?? '' }}" class="about-input px-4 py-3 border border-gray-300 rounded-lg" placeholder="Role">
                                                <div class="md:col-span-2">
                                                    <input data-about-repeat="profiles" data-index="{{ $loop->index }}" data-key="img" type="hidden" value="{{ $profile['img'] ?? '' }}" class="about-input about-image-path">
                                                    <div class="flex flex-wrap items-center gap-4 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                        <img src="{{ $aboutAssetAdmin($profile['img'] ?? '') }}" alt="{{ $profile['name'] ?? 'Profile image' }}" class="h-20 w-20 rounded-md object-cover border">
                                                        <label class="inline-flex cursor-pointer items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                                            <i class="fas fa-image mr-2"></i>
                                                            Change Image
                                                            <input data-about-upload="profiles" data-index="{{ $loop->index }}" data-key="img" type="file" name="about_images[profiles][{{ $loop->index }}][img]" accept="image/*" class="sr-only about-image-upload">
                                                        </label>
                                                        <span class="text-xs text-gray-500">Current: {{ $profile['img'] ?? '' }}</span>
                                                    </div>
                                                </div>
                                                <textarea data-about-repeat="profiles" data-index="{{ $loop->index }}" data-key="desc" rows="3" class="about-input px-4 py-3 border border-gray-300 rounded-lg md:col-span-2" placeholder="Description">{{ $profile['desc'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">What We Do</h3>
                                <input data-about-field="what_we_do_title" type="text" value="{{ $aboutContent['what_we_do_title'] }}" class="about-input mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Section title">
                                <textarea data-about-field="what_we_do_description" rows="2" class="about-input mb-4 w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Section description">{{ $aboutContent['what_we_do_description'] }}</textarea>
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach($aboutContent['what_we_do'] as $item)
                                        <div class="rounded-lg border border-gray-200 bg-white p-4">
                                            <input data-about-repeat="what_we_do" data-index="{{ $loop->index }}" data-key="title" type="text" value="{{ $item['title'] ?? '' }}" class="about-input mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Title">
                                            <input data-about-repeat="what_we_do" data-index="{{ $loop->index }}" data-key="img" type="hidden" value="{{ $item['img'] ?? '' }}" class="about-input about-image-path">
                                            <div class="mb-3 flex flex-wrap items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                <img src="{{ $aboutAssetAdmin($item['img'] ?? '') }}" alt="{{ $item['title'] ?? 'Item image' }}" class="h-16 w-16 rounded-md object-cover border">
                                                <label class="inline-flex cursor-pointer items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700">
                                                    <i class="fas fa-image mr-2"></i>
                                                    Change
                                                    <input data-about-upload="what_we_do" data-index="{{ $loop->index }}" data-key="img" type="file" name="about_images[what_we_do][{{ $loop->index }}][img]" accept="image/*" class="sr-only about-image-upload">
                                                </label>
                                            </div>
                                            <textarea data-about-repeat="what_we_do" data-index="{{ $loop->index }}" data-key="desc" rows="2" class="about-input w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Description">{{ $item['desc'] ?? '' }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How We Do</h3>
                                <input data-about-field="how_we_do_title" type="text" value="{{ $aboutContent['how_we_do_title'] }}" class="about-input mb-4 w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Section title">
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach($aboutContent['how_we_do'] as $item)
                                        <div class="rounded-lg border border-gray-200 bg-white p-4">
                                            <input data-about-repeat="how_we_do" data-index="{{ $loop->index }}" data-key="title" type="text" value="{{ $item['title'] ?? '' }}" class="about-input mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Title">
                                            <input data-about-repeat="how_we_do" data-index="{{ $loop->index }}" data-key="img" type="hidden" value="{{ $item['img'] ?? '' }}" class="about-input about-image-path">
                                            <div class="mb-3 flex flex-wrap items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                <img src="{{ $aboutAssetAdmin($item['img'] ?? '') }}" alt="{{ $item['title'] ?? 'Item image' }}" class="h-16 w-16 rounded-md object-cover border">
                                                <label class="inline-flex cursor-pointer items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700">
                                                    <i class="fas fa-image mr-2"></i>
                                                    Change
                                                    <input data-about-upload="how_we_do" data-index="{{ $loop->index }}" data-key="img" type="file" name="about_images[how_we_do][{{ $loop->index }}][img]" accept="image/*" class="sr-only about-image-upload">
                                                </label>
                                            </div>
                                            <textarea data-about-repeat="how_we_do" data-index="{{ $loop->index }}" data-key="desc" rows="2" class="about-input w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Description">{{ $item['desc'] ?? '' }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Video</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <input data-about-field="video_bg" type="hidden" value="{{ $aboutContent['video_bg'] }}" class="about-input about-image-path">
                                        <div class="flex flex-wrap items-center gap-4 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                            <img src="{{ $aboutAssetAdmin($aboutContent['video_bg']) }}" alt="Video background" class="h-20 w-28 rounded-md object-cover border">
                                            <label class="inline-flex cursor-pointer items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                                <i class="fas fa-image mr-2"></i>
                                                Change Image
                                                <input data-about-upload-field="video_bg" type="file" name="about_images[video_bg]" accept="image/*" class="sr-only about-image-upload">
                                            </label>
                                        </div>
                                    </div>
                                    <input data-about-field="video_url" type="text" value="{{ $aboutContent['video_url'] }}" class="about-input px-4 py-3 border border-gray-300 rounded-lg" placeholder="Video URL">
                                </div>
                            </div>
                        </div>

                        <aside class="xl:sticky xl:top-6 self-start rounded-lg border border-gray-200 bg-white shadow-lg overflow-hidden">
                            <div class="border-b border-gray-200 px-5 py-4">
                                <h3 class="font-semibold text-gray-900">Live Preview</h3>
                            </div>
                            <div class="max-h-[760px] overflow-auto bg-[#f9f8f3] p-5" id="about-preview">
                                <div class="bg-[#1f1b2e] px-5 py-8 text-center text-white">
                                    <h2 data-preview="title" class="text-3xl font-semibold">{{ $aboutContent['title'] }}</h2>
                                </div>
                                <section class="bg-white p-5">
                                    <p class="text-xs uppercase tracking-[0.28em] text-[#BB976D]">Carom Studios</p>
                                    <h3 data-preview="story_title" class="mt-3 text-2xl font-semibold text-gray-900">{{ $aboutContent['story_title'] }}</h3>
                                    <div data-preview="story_paragraphs" class="mt-3 space-y-2 text-sm leading-6 text-gray-600"></div>
                                </section>
                                <section class="grid grid-cols-1 gap-3 p-5" data-preview-list="profiles"></section>
                                <section class="bg-white p-5">
                                    <h3 data-preview="what_we_do_title" class="text-center text-2xl font-semibold uppercase text-gray-900">{{ $aboutContent['what_we_do_title'] }}</h3>
                                    <p data-preview="what_we_do_description" class="mt-2 text-center text-sm leading-6 text-gray-600">{{ $aboutContent['what_we_do_description'] }}</p>
                                    <div class="mt-4 grid grid-cols-2 gap-3" data-preview-list="what_we_do"></div>
                                </section>
                                <section class="p-5">
                                    <h3 data-preview="how_we_do_title" class="text-center text-2xl font-semibold uppercase text-gray-900">{{ $aboutContent['how_we_do_title'] }}</h3>
                                    <div class="mt-4 grid grid-cols-2 gap-3" data-preview-list="how_we_do"></div>
                                </section>
                                <section class="p-5">
                                    <div data-preview="video_bg" class="flex h-44 items-center justify-center bg-cover bg-center text-white">
                                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-white text-gray-900"><i class="fas fa-play"></i></span>
                                    </div>
                                </section>
                            </div>
                        </aside>
                    </div>
                @elseif($isContactPage)
                    <input type="hidden" name="content" id="content" value="{{ e(json_encode($contactContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}">
                    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_420px] gap-6" id="contact-editor">
                        <div class="space-y-6">
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Content</h3>
                                <textarea id="contact_content_input" rows="14" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contact page content">{{ $contactContent['content'] ?? '' }}</textarea>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Image</h3>
                                <input id="contact_image_path" type="hidden" value="{{ $contactContent['image'] ?? 'assets/img/thumb/contact-thumb.jpg' }}">
                                <div class="flex flex-wrap items-center gap-4 rounded-lg border border-gray-200 bg-white p-3">
                                    <img id="contact_image_admin_preview" src="{{ $aboutAssetAdmin($contactContent['image'] ?? 'assets/img/thumb/contact-thumb.jpg') }}" alt="Contact image" class="h-24 w-32 rounded-md object-cover border">
                                    <label class="inline-flex cursor-pointer items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                        <i class="fas fa-image mr-2"></i>
                                        Change Image
                                        <input id="contact_image_upload" type="file" name="page_images[contact_image]" accept="image/*" class="sr-only">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <aside class="xl:sticky xl:top-6 self-start rounded-lg border border-gray-200 bg-white shadow-lg overflow-hidden">
                            <div class="border-b border-gray-200 px-5 py-4">
                                <h3 class="font-semibold text-gray-900">Live Preview</h3>
                            </div>
                            <div class="max-h-[760px] overflow-auto bg-[#f9f8f3]" id="contact-preview">
                                <div class="bg-[#1f1b2e] px-5 py-8 text-center text-white">
                                    <h2 id="contact_preview_title" class="text-3xl font-semibold">{{ old('title', $page->title) }}</h2>
                                </div>
                                <div class="p-5">
                                    <div id="contact_preview_content" class="rounded-md bg-white p-5 text-sm leading-6 text-gray-700"></div>
                                    <img id="contact_preview_image" src="{{ $aboutAssetAdmin($contactContent['image'] ?? 'assets/img/thumb/contact-thumb.jpg') }}" alt="Contact image" class="mt-5 h-52 w-full rounded-md object-cover">
                                </div>
                            </div>
                        </aside>
                    </div>
                @elseif($hasCmsPreview)
                    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_420px] gap-6" id="cms-live-editor">
                        <textarea name="content" id="content" rows="20" required class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-300 @enderror">{{ old('content', $page->content) }}</textarea>
                        <aside class="xl:sticky xl:top-6 self-start rounded-lg border border-gray-200 bg-white shadow-lg overflow-hidden">
                            <div class="border-b border-gray-200 px-5 py-4">
                                <h3 class="font-semibold text-gray-900">Live Preview</h3>
                            </div>
                            <div class="max-h-[760px] overflow-auto bg-[#f9f8f3]">
                                <div class="bg-[#1f1b2e] px-5 py-8 text-center text-white">
                                    <h2 id="cms_preview_title" class="text-3xl font-semibold">{{ old('title', $page->title) }}</h2>
                                </div>
                                <article id="cms_preview_content" class="m-5 rounded-md bg-white p-5 text-sm leading-6 text-gray-700"></article>
                            </div>
                        </aside>
                    </div>
                @else
                    <textarea name="content" id="content" rows="20" required class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-300 @enderror">{{ old('content', $page->content) }}</textarea>
                @endif
=======
                <textarea name="content" id="content" rows="20" required class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-300 @enderror">{{ old('content', $page->content) }}</textarea>
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meta_title') border-red-300 @enderror">
                    @error('meta_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('meta_description') border-red-300 @enderror">{{ old('meta_description', $page->meta_description) }}</textarea>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
<<<<<<< HEAD

            @include('admin.seo.partials.advanced-fields', [
                'seoMetadata' => $seoMetadata ?? null,
                'includeTitleFields' => false,
                'titleFallback' => old('meta_title', $page->meta_title),
                'descriptionFallback' => old('meta_description', $page->meta_description),
                'canonicalFallback' => url($page->slug),
                'schemaType' => 'WebPage',
            ])
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('admin.cms.index') }}" class="px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white shadow-sm text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-save mr-2"></i>
                Update Page
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const aboutEditor = document.getElementById('about-editor');

    titleInput.addEventListener('input', function() {
        if (aboutEditor) {
            return;
        }

=======
    document.getElementById('title').addEventListener('input', function() {
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        let title = this.value;
        let slug = title.toLowerCase().trim()
            .replace(/[^\\w\\s-]/g, '')
            .replace(/[\\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
<<<<<<< HEAD
        slugInput.value = slug;
    });

    if (!aboutEditor) {
        const cmsLiveEditor = document.getElementById('cms-live-editor');
        const contactEditor = document.getElementById('contact-editor');

        if (cmsLiveEditor) {
            const content = document.getElementById('content');
            const previewContent = document.getElementById('cms_preview_content');
            const previewTitle = document.getElementById('cms_preview_title');
            const updateCmsPreview = () => {
                previewTitle.textContent = titleInput.value || '';
                previewContent.innerHTML = content.value || '';
            };

            titleInput.addEventListener('input', updateCmsPreview);
            content.addEventListener('input', updateCmsPreview);
            updateCmsPreview();
        }

        if (contactEditor) {
            const contentInput = document.getElementById('content');
            const contactContentInput = document.getElementById('contact_content_input');
            const contactImagePath = document.getElementById('contact_image_path');
            const contactImageUpload = document.getElementById('contact_image_upload');
            const contactImageAdminPreview = document.getElementById('contact_image_admin_preview');
            const contactPreviewContent = document.getElementById('contact_preview_content');
            const contactPreviewImage = document.getElementById('contact_preview_image');
            const contactPreviewTitle = document.getElementById('contact_preview_title');
            let contactUploadPreview = null;

            const assetBase = @json(asset(''));
            const storageBase = @json(asset('storage'));
            const assetUrl = (path) => {
                path = String(path || '').trim();
                if (!path) return '';
                if (/^(https?:)?\\/\\//.test(path) || path.startsWith('/')) return path;
                if (path.startsWith('assets/') || path.startsWith('storage/')) return assetBase + path.replace(/^\\/+/, '');
                return storageBase + '/' + path.replace(/^\\/+/, '');
            };

            const updateContactPreview = () => {
                const payload = {
                    content: contactContentInput.value,
                    image: contactImagePath.value || 'assets/img/thumb/contact-thumb.jpg',
                };

                contentInput.value = JSON.stringify(payload, null, 2);
                contactPreviewTitle.textContent = titleInput.value || '';
                contactPreviewContent.innerHTML = contactContentInput.value || '';
                contactPreviewImage.src = contactUploadPreview || assetUrl(payload.image);
            };

            titleInput.addEventListener('input', updateContactPreview);
            contactContentInput.addEventListener('input', updateContactPreview);
            contactImageUpload.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
                contactUploadPreview = URL.createObjectURL(file);
                contactImageAdminPreview.src = contactUploadPreview;
                updateContactPreview();
            });
            updateContactPreview();
        }

        return;
    }

    const contentInput = document.getElementById('content');
    const assetBase = @json(asset(''));
    const storageBase = @json(asset('storage'));
    let currentContent = {};
    const uploadPreviews = {};

    try {
        currentContent = JSON.parse(contentInput.value || '{}');
    } catch (error) {
        currentContent = {};
    }

    const assetUrl = (path) => {
        path = String(path || '').trim();
        if (!path) {
            return '';
        }
        if (/^(https?:)?\\/\\//.test(path) || path.startsWith('/')) {
            return path;
        }
        if (path.startsWith('assets/') || path.startsWith('storage/')) {
            return assetBase + path.replace(/^\\/+/, '');
        }
        return storageBase + '/' + path.replace(/^\\/+/, '');
    };

    const escapeHtml = (value) => String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const collectAboutContent = () => {
        const data = JSON.parse(JSON.stringify(currentContent));

        document.querySelectorAll('[data-about-field]').forEach((field) => {
            const key = field.dataset.aboutField;
            data[key] = key === 'story_paragraphs'
                ? field.value.split('\\n').map((item) => item.trim()).filter(Boolean)
                : field.value;
        });

        document.querySelectorAll('[data-about-repeat]').forEach((field) => {
            const group = field.dataset.aboutRepeat;
            const index = Number(field.dataset.index);
            const key = field.dataset.key;

            data[group] = data[group] || [];
            data[group][index] = data[group][index] || {};
            data[group][index][key] = field.value;
        });

        return data;
    };

    const renderPreviewCards = (selector, items, group) => {
        const target = document.querySelector(selector);

        if (!target) {
            return;
        }

        target.innerHTML = (items || []).map((item, index) => `
            <article class="overflow-hidden rounded-md border border-gray-200 bg-white">
                ${(item.img || uploadPreviews[`${group}.${index}.img`]) ? `<img src="${uploadPreviews[`${group}.${index}.img`] || assetUrl(item.img)}" alt="${escapeHtml(item.name || item.title || '')}" class="h-24 w-full object-cover">` : ''}
                <div class="p-3">
                    <h4 class="text-sm font-semibold text-gray-900">${escapeHtml(item.name || item.title || '')}</h4>
                    ${item.title && item.name ? `<p class="mt-1 text-xs text-[#BB976D]">${escapeHtml(item.title)}</p>` : ''}
                    <p class="mt-2 text-xs leading-5 text-gray-600">${escapeHtml(item.desc || '')}</p>
                </div>
            </article>
        `).join('');
    };

    const updateAboutPreview = () => {
        const data = collectAboutContent();
        currentContent = data;
        contentInput.value = JSON.stringify(data, null, 2);

        document.querySelector('[data-preview="title"]').textContent = data.title || '';
        document.querySelector('[data-preview="story_title"]').textContent = data.story_title || '';
        document.querySelector('[data-preview="what_we_do_title"]').textContent = data.what_we_do_title || '';
        document.querySelector('[data-preview="what_we_do_description"]').textContent = data.what_we_do_description || '';
        document.querySelector('[data-preview="how_we_do_title"]').textContent = data.how_we_do_title || '';

        const paragraphs = document.querySelector('[data-preview="story_paragraphs"]');
        paragraphs.innerHTML = (data.story_paragraphs || []).map((paragraph) => `<p>${escapeHtml(paragraph)}</p>`).join('');

        renderPreviewCards('[data-preview-list="profiles"]', data.profiles || [], 'profiles');
        renderPreviewCards('[data-preview-list="what_we_do"]', data.what_we_do || [], 'what_we_do');
        renderPreviewCards('[data-preview-list="how_we_do"]', data.how_we_do || [], 'how_we_do');

        const videoBg = document.querySelector('[data-preview="video_bg"]');
        videoBg.style.backgroundImage = uploadPreviews.video_bg
            ? `url("${uploadPreviews.video_bg}")`
            : (data.video_bg ? `url("${assetUrl(data.video_bg)}")` : '');
    };

    document.querySelectorAll('.about-input').forEach((field) => {
        field.addEventListener('input', updateAboutPreview);
    });

    document.querySelectorAll('.about-image-upload').forEach((field) => {
        field.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            const wrapper = this.closest('div');
            const image = wrapper ? wrapper.querySelector('img') : null;

            if (image) {
                image.src = previewUrl;
            }

            if (this.dataset.aboutUploadField) {
                uploadPreviews[this.dataset.aboutUploadField] = previewUrl;
            } else {
                uploadPreviews[`${this.dataset.aboutUpload}.${this.dataset.index}.${this.dataset.key}`] = previewUrl;
            }

            updateAboutPreview();
        });
    });

    updateAboutPreview();
=======
        document.getElementById('slug').value = slug;
    });
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
});
</script>
@endpush
@endsection

