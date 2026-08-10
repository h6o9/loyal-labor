<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">

        @if (!getSettingStatus('search_engine_indexing'))
            <meta name="robots" content="noindex,nofollow" />
        @endif

        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

        @stack('meta')
        @php
            $seo = seoSetting(Route::currentRouteName()) ?? false;
            $customCode = customCode();
        @endphp

        @if ($seo && isset($seo?->seo_title) && $seo?->seo_title && filled($seo?->seo_title))
            <title>{{ $seo->seo_title ?? __('TopCommerce') . ' - ' . $setting->app_name }}</title>

            <meta property="og:title" content=" {{ $seo->seo_title ?? __('TopCommerce') }} - {{ $setting->app_name }}">
            <meta property="og:site_name" content="{{ $setting->app_name }}">
            <meta property="og:url" content="{{ url()->current() }}">
            <meta property="og:description" content=" {{ $seo->seo_description ?? $setting->app_name }}">
            <meta name="description" content="{{ $seo->seo_description ?? $setting->app_name }}">
            <meta property="og:type" content="website">
            <meta property="og:image" content="{{ asset($setting->breadcrumb_image) }}">
        @else
            <title>@yield('title', __('Loyal Labor'))</title>
        @endif

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if ($setting->favicon)
            <link type="image/png" href="{{ asset($setting->favicon) }}" rel="icon">
        @endif

        @include('website.layouts.css.styles', ['setting' => $setting])

        @stack('styles')

        <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>

        @if (isset($customCode) && $customCode?->header_javascript && filled($customCode->header_javascript))
            <script>
                "use strict";

                {!! $customCode->header_javascript !!}
            </script>
        @endif

        @if (getSettingStatus('googel_tag_status'))
            <script>
                window.dataLayer = window.dataLayer || [];

                @if (session('gtm_push'))
                    @foreach ((array) session('gtm_push') as $event)
                        window.dataLayer.push({!! json_encode($event) !!});
                    @endforeach
                @endif
            </script>
            @stack('gtm-data')
        @endif

        @include('website.layouts.js.3rd-party-api', ['setting' => $setting])

        @include('website.layouts.js.dynamic-objects')
        @stack('scripts-top')
    </head>

    <body class="home_{{ config('services.theme') ?? 1 }}">
        @if (getSettingStatus('googel_tag_status'))
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $setting?->googel_tag_id }}"
                    style="display:none;visibility:hidden" height="0" width="0"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif

        @if (isset($customCode) && $customCode?->body_javascript && filled($customCode->body_javascript))
            <script>
                "use strict";

                {!! $customCode->body_javascript !!}
            </script>
        @endif

        @php
            try {
                $top_header_section = (object) [
                    'top_header_section' => setHomepageSections(true)->top_header_section,
                ];
            } catch (\Exception $e) {
                $theme = config('services.theme');
                logError("Unable to get top header section in file topbar/{$theme}.blade.php", $e);
            }
        @endphp

        @includeWhen(isset($top_header_section),
            'website.layouts.partials.topbar.' . config('services.theme'),
            [
                'top_header_section' => isset($top_header_section) ? $top_header_section : [],
            ]
        )

        <section @class([
            'wsus__header',
            'wsus__header_3' => config('services.theme') == 3,
        ])>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="wsus__header_contant">
                            <a class="logo" href="{{ route('website.home') }}">
                                <img class="img-fluid w-100" src="{{ asset($setting->logo) }}" alt="logo">
                            </a>
                            <form class="wsus__header_search_category" id="header_category_based_search"
                                action="{{ route('website.products') }}">
                                <select class="select_2" name="category">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach (allCategories(status: true) as $category)
                                        <option value="{{ $category->slug }}" @selected(request()->get('category') == $category->slug)>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <input name="keyword" type="text" value="{{ request('keyword') }}"
                                    placeholder="{{ __('Search Products') }}">
                                <button title="{{ __('Search') }}"><i class="far fa-search"></i></button>
                            </form>
                            <div class="wsus__header_icon">
                                <ul>
                                    <li>
                                        <a href="{{ route('website.cart') }}"><img class="img-fluid w-100"
                                                src="{{ asset('website/images/add_cart.webp') }}" alt="icon"></a>
                                        <span id="cart_count">
                                            {{ WsusCart::getCartCount() }}
                                        </span>
                                    </li>
                                    <li>
                                        <a href="{{ route('website.wishlist') }}">
                                            <img class="img-fluid w-100" src="{{ asset('website/images/react.webp') }}"
                                                alt="{{ __('Wishlist') }}">
                                        </a>
                                        <span id="wishlist_count">
                                            {{ auth()->check() ? wishlistCount() : 0 }}
                                        </span>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ auth()->check() ? route('website.user.dashboard') : route('login') }}"><img
                                                class="img-fluid w-100" src="{{ asset('website/images/user_1.webp') }}"
                                                alt="icon"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @includeWhen(isset($top_header_section),
            'website.layouts.partials.main-menu.' . config('services.theme'),
            [
                'top_header_section' => isset($top_header_section) ? $top_header_section : [],
                'defaultMenus' => $defaultMenus,
            ]
        )

        @include('website.layouts.partials.sticky-menu.1')

        @yield('content')

        @php
            try {
                $footer_section = (object) [
                    'footer_section' => setHomepageSections(true)->footer_section,
                ];
            } catch (\Exception $e) {
                $theme = config('services.theme');
                logError("Unable to get footer section in file footer/{$theme}.blade.php", $e);
            }
        @endphp

        @includeWhen(isset($footer_section), 'website.layouts.partials.footer.' . config('services.theme'), [
            'footer_section' => isset($footer_section) ? $footer_section : [],
        ])

        <a id="button">
            <div class="scroller"></div>
        </a>

        <div class="cursor"></div>

        @guest
            @include('components::login-modal')
        @endguest

        @include('website.layouts.js.scripts', ['setting' => $setting, 'customCode' => $customCode])

        @stack('scripts')

    </body>

</html>
