        <!--============================
        STICKY MENU START
    ==============================-->
        <div class="mobile_menu_area">
            <div class="mobile_menu_area_top">
                <a class="mobile_menu_logo" href="{{ route('website.home') }}">
                    <img src="{{ asset($setting->logo) }}" alt="{{ $setting->app_name ?? '' }}">
                </a>
                <div class="mobile_menu_icon d-block d-lg-none" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
                    <span class="mobile_menu_icon"><i class="far fa-stream menu_icon_bar"></i></span>
                </div>
            </div>

            <div class="offcanvas {{ session()->get('text_direction', 'ltr') == 'rtl' ? 'offcanvas-end' : 'offcanvas-start' }}"
                id="offcanvasWithBothOptions" data-bs-scroll="true" tabindex="-1">
                <button class="btn-close" data-bs-dismiss="offcanvas" type="button" aria-label="Close"><i
                        class="fal fa-times"></i></button>
                <div class="offcanvas-body">

                    <ul class="mobail_menu_dropdown">
                        @if (allLanguages()->where('status', 1)->count() > 1)
                            <li>
                                <select class="select_js language_change_select">
                                    @foreach (allLanguages()->where('status', 1) as $language)
                                        <option value="{{ $language->code }}" @selected(getSessionLanguage() == $language->code)>
                                            {{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </li>
                        @endif
                        @if (allCurrencies()->where('status', 'active')->count() > 1)
                            <li>
                                <select class="select_js currency_change_select">
                                    @foreach (allCurrencies()->where('status', 'active') as $currency)
                                        <option value="{{ $currency->currency_code }}" @selected(getSessionCurrency() == $currency->currency_code)>
                                            {{ $currency->currency_name }}</option>
                                    @endforeach
                                </select>
                            </li>
                        @endif
                    </ul>

                    <ul class="mobile_menu_header d-flex flex-wrap">
                        <li><a href="{{ route('website.cart') }}"><i class="fal fa-shopping-cart"></i><span
                                    id="cart_count_mobile">
                                    {{ WsusCart::getCartCount() }}
                                </span></a>
                        </li>
                        <li><a href="{{ route('website.wishlist') }}"><i class="fal fa-heart"></i><span>
                                    @if (Auth::check())
                                        {{ Auth::user()->wishlist->count() ?? 0 }}
                                    @else
                                        0
                                    @endif
                                </span></a></li>
                        <li><a href="{{ auth()->check() ? route('website.user.dashboard') : route('login') }}"><i
                                    class="far fa-user"></i></a></li>
                    </ul>

                    <form class="mobile_menu_search" action="{{ route('website.products') }}">
                        <input name="keyword" type="text" placeholder="{{ __('Search') }}">
                        <button type="submit"><i class="far fa-search"></i></button>
                    </form>

                    <div class="mobile_menu_item_area">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">{{ __('Menu') }}</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab"
                                    aria-controls="nav-profile" aria-selected="false">{{ __('Categories') }}</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                aria-labelledby="nav-home-tab" tabindex="0">
                                <ul class="main_mobile_menu">
                                    @forelse (menuGetBySlug($defaultMenus::MAIN_MENU->value) as $menu)
                                        @php
                                            $has_child = isset($menu['child']) && !empty($menu['child']);
                                            $is_home = $menu['link'] == '/';
                                        @endphp

                                        @if ($is_home && $setting?->show_all_homepage == 1)
                                            <li class="mobile_dropdown">
                                                <a class="{{ isRoute('website.home', 'active') }}" href="#"
                                                    role="button" rel="nofollow">{{ __('Home') }}</a>
                                                <ul class="inner_menu">
                                                    @php
                                                        $currentTheme = request()->query(
                                                            'theme',
                                                            config('services.theme'),
                                                        );
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
                                                </ul>
                                            </li>
                                        @else
                                            <li
                                                class="mobile_dropdown {{ $has_child ? '' : 'mobile_drop_memu_none' }}">
                                                <a class="{{ url()->current() == url($menu['link']) || hasActiveChild($menu) ? 'active' : '' }}"
                                                    href="{{ $has_child ? 'javascript:void(0);' : url($menu['link']) }}"
                                                    {{ $menu['link'] == '#' || empty($menu['link']) ? 'href="javascript:;" role="button" rel="nofollow"' : '' }}
                                                    {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}
                                                </a>
                                                @if ($has_child)
                                                    <ul class="inner_menu">
                                                        @foreach ($menu['child'] as $child)
                                                            <x-child-menu-mobile :menu="$child" :isChild=true />
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endif
                                    @empty
                                        <li
                                            class="mobile_dropdown {{ $setting?->show_all_homepage !== 1 ? 'mobile_drop_memu_none' : '' }}">
                                            @if ($setting?->show_all_homepage == 1)
                                                <a class="{{ isRoute('website.home', 'active') }}" href="#"
                                                    role="button" rel="nofollow">{{ __('Home') }}</a>
                                                <ul class="inner_menu">
                                                    @php
                                                        $currentTheme = request()->query(
                                                            'theme',
                                                            config('services.theme'),
                                                        );
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
                                                </ul>
                                            @else
                                                <a class="{{ isRoute('website.home', 'active') }}"
                                                    href="{{ route('website.home') }}">{{ __('Home') }}</a>
                                            @endif
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab" tabindex="0">
                                <ul class="main_mobile_menu">
                                    @foreach (allCategories(status: true, parentOnly: true, withChild: true) as $category)
                                        <li class="mobile_dropdown">
                                            <a
                                                href="{{ $category?->children?->isNotEmpty() ? 'javascript:void(0);' : route('website.products', ['category' => $category->slug]) }}"
                                                {{ $category?->children?->isNotEmpty() ? 'href="javascript:;" role="button" rel="nofollow"' : '' }}>
                                                <span>
                                                    <img class="img-fluid w-100"
                                                        src="{{ asset($category->icon ? $category->icon : $setting->default_avatar) }}"
                                                        alt="icon">
                                                </span>
                                                {{ $category->name }}
                                            </a>
                                            @if ($category?->children->isNotEmpty())
                                                <ul class="inner_menu">
                                                    @foreach ($category->children as $child)
                                                        <li><a
                                                                href="{{ route('website.products', ['category' => $child->slug]) }}">{{ $child->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--============================
    STICKY MENU END
==============================-->
