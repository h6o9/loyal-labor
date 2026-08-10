@php
    $currentTheme = request()->query('theme', config('services.theme'));
@endphp

<li><a class="{{ $currentTheme == 1 ? 'active' : '' }}"
        href="{{ route('website.home') }}?theme=1">{{ __('Home style 01') }}</a>
</li>
<li><a class="{{ $currentTheme == 2 ? 'active' : '' }}"
        href="{{ route('website.home') }}?theme=2">{{ __('Home style 02') }}</a>
</li>
<li><a class="{{ $currentTheme == 3 ? 'active' : '' }}"
        href="{{ route('website.home') }}?theme=3">{{ __('Home style 03') }}</a>
</li>
<li><a class="{{ $currentTheme == 4 ? 'active' : '' }}"
        href="{{ route('website.home') }}?theme=4">{{ __('Home style 04') }}</a>
</li>
