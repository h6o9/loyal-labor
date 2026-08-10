@php
    $has_child = isset($menu['child']) && !empty($menu['child']);
    $isActive = url()->current() == url($menu['link']) || hasActiveChild($menu);
@endphp

<li>
    <a class="{{ !$isChild ? 'nav_link' : '' }} {{ $isActive ? 'active' : '' }}"
        href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
        {{ $menu['link'] == '#' || empty($menu['link']) ? 'href="javascript:;" role="button" rel="nofollow"' : '' }}
        {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>
        {{ $menu['label'] }}

        @if ($has_child)
            <i class="fal fa-angle-down"></i>
        @endif
    </a>

    @if ($has_child)
        <ul class="wsus__droap_menu_2">
            @foreach ($menu['child'] as $child)
                <x-child-menu :menu="$child" :isChild="true" />
            @endforeach
        </ul>
    @endif
</li>
