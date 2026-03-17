@foreach($menus as $menu)
    @php
        $submenus = $menu->children()->where('status', 'active')->orderBy('order')->get();
    @endphp
    @if($submenus->count() > 0)
        <li class="relative parent-parent-menu-item">
            <a href="{{ $menu->url }}" class="home-link {{ request()->is($menu->url) ? 'active' : '' }}">{{ $menu->title }}</a>
            <ul class="sub-menu lg:absolute z-50 lg:top-full lg:left-0 lg:min-w-[220px] lg:opacity-0 lg:invisible lg:transition-all lg:bg-white lg:dark:bg-title lg:py-[15px] lg:pr-[30px]">
                @include('includes.partials.dynamic-menu', ['menus' => $submenus])
            </ul>
        </li>
    @else
        <li><a href="{{ $menu->url }}" class="sub-menu-item {{ request()->is($menu->url) ? 'active' : '' }}">{{ $menu->title }}</a></li>
    @endif
@endforeach

