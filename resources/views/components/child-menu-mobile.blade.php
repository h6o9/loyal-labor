@php
    $has_child = isset($menu['child']) && !empty($menu['child']);
    $isActive = url()->current() == url($menu['link']) || hasActiveChild($menu);
@endphp

<li>
    <a class="{{ $isActive ? 'active' : '' }}"
        href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
        {{ $menu['link'] == '#' || empty($menu['link']) ? 'href="javascript:;" role="button" rel="nofollow"' : '' }}
        {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>
        {{ $menu['label'] }}
    </a>

    @if ($has_child)
        <ul class="inner_menu">
            @foreach ($menu['child'] as $child)
                <x-child-menu-mobile :menu="$child" :isChild="true" />
            @endforeach
        </ul>
    @endif
</li>
