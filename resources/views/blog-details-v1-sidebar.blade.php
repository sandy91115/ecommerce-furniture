<div class="w-full lg:w-80 flex-none grid gap-[15px] sm:grid-cols-2 lg:grid-cols-1 lg:sticky top-5">
    <!-- Categories -->
    <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-[30px]">
        @if(isset($categories) && $categories->count() > 0)
            <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Categories</h4>
            <ul class="grid gap-3 sm:gap-[15px] text-base sm:text-lg text-title dark:text-white list-disc pl-5">
                @foreach($categories as $category)
                <li class="group duration-100 hover:text-primary"><a class="text-underline-primary" href="{{ url('/blog-tag/' . Str::slug($category)) }}">{{ $category }}</a></li>
                @endforeach
            </ul>
        @else
            <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Categories</h4>
            <ul class="grid gap-3 sm:gap-[15px] text-base sm:text-lg text-title dark:text-white list-disc pl-5">
                <li class="group duration-100 hover:text-primary"><a class="text-underline-primary" href="#">Chair</a></li>
                <li class="group duration-100 hover:text-primary"><a class="text-underline-primary" href="#">Table</a></li>
                <li class="group duration-100 hover:text-primary"><a class="text-underline-primary" href="#">Sofa Set</a></li>
                <li class="group duration-100 hover:text-primary"><a class="text-underline-primary" href="#">Lamp</a></li>
                <li class="group duration-100 hover:text-primary"><a class="text-underline-primary" href="#">Vases</a></li>
            </ul>
        @endif
    </div>
    <!-- Recent Posts -->
    <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-[30px]">
        @if(isset($recentBlogs) && $recentBlogs->count() > 0)
            <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Recent Posts</h4>
            <div class="grid gap-[10px]">
                @foreach($recentBlogs as $recent)
                <h6 class="font-medium group text-base sm:text-lg pb-[10px] border-b border-bdr-clr dark:border-[#172430]">
                    <a class="text-underline" href="{{ route('blog.show', $recent->slug) }}">{{ Str::limit($recent->title, 60) }}</a>
                </h6>
                @endforeach
            </div>
        @else
            <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Recent Posts</h4>
            <div class="grid gap-[10px]">
                <h6 class="font-medium group text-base sm:text-lg pb-[10px] border-b border-bdr-clr dark:border-[#172430]"><a class="text-underline" href="{{ url('/blog-details-v1') }}">Auctor sit elementum habitant vel tempor varius. </a></h6>
                <h6 class="font-medium group text-base sm:text-lg pb-[10px] border-b border-bdr-clr dark:border-[#172430]"><a class="text-underline" href="{{ url('/blog-details-v2') }}">Consectetur purus habitasse ut diam habitant varius.</a></h6>
                <h6 class="font-medium group text-base sm:text-lg pb-[10px] border-b border-bdr-clr dark:border-[#172430]"><a class="text-underline" href="{{ url('/blog-details-v3') }}">Auctor sit elementum habitant vel tempor varius. </a></h6>
            </div>
        @endif
    </div>
    <!-- Popular Tags -->
    <div class="bg-[#F8F8F9] dark:bg-dark-secondary p-5 sm:p-[30px]">
        @if(isset($tags) && count($tags) > 0)
            <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Popular Tags</h4>
            <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                @foreach(array_slice($tags, 0, 10) as $tag)
                <a class="btn btn-outline btn-xs" href="{{ url('/blog-tag/' . Str::slug($tag)) }}"><span>{{ $tag }}</span></a>
                @endforeach
            </div>
        @else
            <h4 class="font-medium leading-none text-xl sm:text-2xl mb-5 sm:mb-6">Popular Tags</h4>
            <div class="flex flex-wrap gap-[10px] md:gap-[15px] mt-5 md:mt-6">
                <a class="btn btn-outline btn-xs" href="#"><span>Chair</span></a>
                <a class="btn btn-outline btn-xs" href="#"><span>Art & Paint</span></a>
                <a class="btn btn-outline btn-xs" href="#"><span>Mirror</span></a>
                <a class="btn btn-outline btn-xs" href="#"><span>Table</span></a>
                <a class="btn btn-outline btn-xs" href="#"><span>Lamp</span></a>
            </div>
        @endif
    </div>
</div>
