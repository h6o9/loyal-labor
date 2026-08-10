<ul class="wsus__main_menu_nav">
    @forelse (menuGetBySlug($defaultMenus::MAIN_MENU->value) as $menu)
        @php
            $has_child = isset($menu['child']) && !empty($menu['child']);
            $is_home = $menu['link'] == '/';
        @endphp

        @if ($is_home && $setting?->show_all_homepage == 1)
            <li>
                <a class="nav_link {{ isRoute('website.home', 'active') }}"
                    href="{{ route('website.home') }}">{{ __('Home') }}<i
                        class="fal fa-angle-down"></i></a>
                <ul class="wsus__droap_menu_2">
                    @include('website.layouts.partials.demo-homepages')
                </ul>
            </li>
        @else
            <li>
                <a class="nav_link {{ url()->current() == url($menu['link']) || hasActiveChild($menu) ? 'active' : '' }}"
                    href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                    {{ $menu['link'] == '#' || empty($menu['link']) ? 'href="javascript:;" role="button" rel="nofollow"' : '' }}
                    {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}
                    @if ($has_child)
                        <i
                            class="fal fa-angle-down"></i>
                    @endif
                </a>
                @if ($has_child)
                    <ul class="wsus__droap_menu_2">
                        @foreach ($menu['child'] as $child)
                            <x-child-menu :menu="$child" :isChild=true />
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @empty
        <li>
            @if ($setting?->show_all_homepage == 1)
                <a class="nav_link {{ isRoute('website.home', 'active') }}"
                    href="{{ route('website.home') }}">{{ __('Home') }}<i
                        class="fal fa-angle-down"></i></a>
                <ul class="wsus__droap_menu_2">
                    @include('website.layouts.partials.demo-homepages')
                </ul>
            @else
                <a class="nav_link {{ isRoute('website.home', 'active') }}"
                    href="{{ route('website.home') }}">{{ __('Home') }}</a>
            @endif
        </li>
    @endforelse
</ul>
