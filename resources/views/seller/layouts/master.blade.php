@php
    $user = Auth::guard('web')->user();
@endphp

<!DOCTYPE html>
<html lang="en">

    <head>
        <link type="image/x-icon" href="{{ asset($setting->favicon) }}" rel="shortcut icon">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('title')
        <link href="{{ asset($setting->favicon) }}" rel="icon">
        @include('seller.layouts.partials.css')
        @stack('css')
    </head>

    <body>
        <div id="app">
            <div class="main-wrapper">
                <div class="navbar-bg"></div>
                <nav class="navbar navbar-expand-lg main-navbar px-3 py-2">
                    <div class="me-2 form-inline">
                        <ul class="me-3 navbar-nav d-flex align-items-center">
                            <li><a class="nav-link nav-link-lg" data-toggle="sidebar" href="#"><i
                                        class="fas fa-bars"></i></a></li>
                            <li class="dropdown border rounded-2 mx-2 dropdown-list-toggle">
                                <a class="nav-link nav-link-lg" href="{{ route('website.home') }}" target="_blank">
                                    <i class="fas fa-home"></i> {{ __('Visit Website') }}</i>
                                </a>
                            </li>
                            @if (Module::isEnabled('Language') && Route::has('set-language') && allLanguages()?->where('status', 1)->count() > 1)
                                <li class="setLanguageHeader dropdown border rounded-2"><a
                                        class="nav-link dropdown-toggle nav-link-lg nav-link-user"
                                        data-bs-toggle="dropdown" href="javascript:;">
                                        <div class="d-sm-none d-lg-inline-block">
                                            {{ allLanguages()?->firstWhere('code', getSessionLanguage())?->name ?? __('Select language') }}
                                        </div>
                                    </a>
                                    <div class="dropdown-menu py-0 dropdown-menu-left">
                                        @forelse (allLanguages()?->where('status', 1) as $language)
                                            <a class="dropdown-item has-icon {{ getSessionLanguage() == $language->code ? 'bg-light' : '' }}"
                                                href="{{ getSessionLanguage() == $language->code ? 'javascript:;' : route('set-language', ['code' => $language->code]) }}">
                                                {{ $language->name }}
                                            </a>
                                        @empty
                                            <a class="dropdown-item has-icon {{ getSessionLanguage() == 'en' ? 'bg-light' : '' }}"
                                                href="javascript:;">
                                                {{ __('English') }}
                                            </a>
                                        @endforelse
                                    </div>
                                </li>
                            @endif
                            @if (Module::isEnabled('Currency') &&
                                    Route::has('set-currency') &&
                                    allCurrencies()?->where('status', 'active')->count() > 1)
                                <li class="set-currency-header dropdown border rounded-2 mx-2"><a
                                        class="nav-link dropdown-toggle nav-link-lg nav-link-user"
                                        data-bs-toggle="dropdown" href="javascript:;">
                                        <div class="d-sm-none d-lg-inline-block">
                                            {{ allCurrencies()?->firstWhere('currency_code', getSessionCurrency())?->currency_name ?? __('Select Currency') }}
                                        </div>
                                    </a>
                                    <div class="dropdown-menu py-0 dropdown-menu-left">
                                        @forelse (allCurrencies()?->where('status', 'active') as $currency)
                                            <a class="dropdown-item has-icon {{ getSessionCurrency() == $currency->currency_code ? 'bg-light' : '' }}"
                                                href="{{ getSessionCurrency() == $currency->currency_code ? 'javascript:;' : route('set-currency', ['currency' => $currency->currency_code]) }}">
                                                {{ $currency->currency_name }}
                                            </a>
                                        @empty
                                            <a class="dropdown-item has-icon {{ getSessionCurrency() == 'USD' ? 'bg-light' : '' }}"
                                                href="javascript:;">
                                                {{ __('USD') }}
                                            </a>
                                        @endforelse
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="mr-auto me-md-auto search-box position-relative">

                    </div>

                    <ul class="navbar-nav">

                        @include('vendor::layouts.partials.notifications')

                        <li class="dropdown"><a
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user" data-bs-toggle="dropdown"
                                href="javascript:;">
                                <img class="me-1 rounded-circle"
                                    src="{{ $user->image ? asset($user->image) : asset($setting->default_user_image) }}"
                                    alt="image">

                                <div class="d-sm-none d-lg-inline-block">{{ $user->name }}</div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item has-icon d-flex align-items-center {{ isRoute(['seller.my-profile'], 'text-primary') }}"
                                    href="{{ route('seller.my-profile') }}">
                                    <i class="far fa-user"></i> {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item has-icon d-flex align-items-center {{ isRoute(['seller.shop-profile'], 'text-primary') }}"
                                    href="{{ route('seller.shop-profile') }}">
                                    <i class="far fa-user"></i> {{ __('Shop Profile') }}
                                </a>
                                <a class="dropdown-item has-icon d-flex align-items-center" href="javascript:;"
                                    onclick="event.preventDefault(); $('#user-logout-form').trigger('submit');">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>

                @include('seller.layouts.partials.sidebar')

                @yield('seller-content')

                <footer class="main-footer">
                    <div class="footer-left">
                        {{ $setting->copyright_text }}
                    </div>
                    <div class="footer-right">
                        <span>{{ __('Version') }}: {{ $setting->version ?? '' }}</span>
                    </div>
                </footer>

            </div>
        </div>

        <form class="d-none" id="user-logout-form" action="{{ route('logout') }}" method="GET">
            @csrf
        </form>

        <x-admin.delete-modal />

        @include('seller.layouts.partials.js')

        <script>
            var base_url = "{{ url('/') }}";
            var isDemo = "{{ strtolower(config('app.app_mode')) }}";
            var demo_mode_error = "{{ __('In Demo Mode You Can Not Perform This Action') }}";
            var translation_success = "{{ __('Translated Successfully!') }}";
            var translation_processing = "{{ __('Translation Processing, please wait...') }}";
            var errorThrown = "{{ __('Error') }}";
            var translate_to = "{{ __('Translate to') }}";
            var basic_error_message = "{{ __('Something went wrong') }}";
        </script>
        @stack('js')

    </body>

</html>
