<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">
        @if (!getSettingStatus('search_engine_indexing'))
            <meta name="robots" content="noindex,nofollow" />
        @endif
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />

        @php
            $seo = seoSetting(Route::currentRouteName());
            $customCode = customCode();
        @endphp

        @if ($seo)
            <title>{{ $seo->seo_title ?? __('TopCommerce') . ' - ' . $setting->app_name }}</title>

            <meta property="og:title" content=" {{ $seo->seo_title ?? __('TopCommerce') }} - {{ $setting->app_name }}">
            <meta property="og:site_name" content="{{ $setting->app_name }}">
            <meta property="og:url" content="{{ url()->current() }}">
            <meta property="og:description" content=" {{ $seo->seo_description ?? $setting->app_name }}">
            <meta property="og:type" content="website">
            <meta property="og:image" content="{{ asset($setting->breadcrumb_image) }}">
        @else
            @stack('meta')
            <title>@yield('title', __('Authentication')) - {{ $setting->app_name }}</title>
        @endif

        <link type="image/png" href="{{ asset('website/images/favicon.webp') }}" rel="icon">

        @include('website.layouts.css.styles')

        <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>

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

        @if (isset($customCode) && $customCode?->header_javascript && filled($customCode->header_javascript))
            <script>
                "use strict";

                {!! $customCode->header_javascript !!}
            </script>
        @endif
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

        @yield('content')

        @include('website.layouts.js.scripts', ['setting' => $setting, 'customCode' => $customCode])
    </body>

</html>
