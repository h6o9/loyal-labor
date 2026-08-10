@if (isset($footer_section) && $footer_section->footer_section->status == 1)

    <!--============================
        FOOTER 3 START
    ==============================-->
    <footer class="wsus__footer wsus__footer_3 pt_120 xs_pt_100"
        style="background: url({{ asset('website/images/footer_3_bg.webp') }});">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3 wow fadeInUp">
                    <div class="wsus__footer_social_media">
                        <a class="logo" href="{{ route('website.home') }}">
                            <img class="img-fluid w-100" src="{{ asset($setting->logo_dark) }}" alt="logo">
                        </a>
                        <p>
                            {{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'footer_subtitle') }}
                        </p>
                        <ul>
                            @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'facebook_status') == 'active')
                                <li><a
                                        href="{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'facebook_link') }}"><img
                                            class="img-fluid w-100" src="{{ asset('website/images/facebook.webp') }}"
                                            alt="icon"></a>
                                </li>
                            @endif
                            @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'x_status') == 'active')
                                <li><a
                                        href="{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'x_link') }}"><img
                                            class="img-fluid w-100" src="{{ asset('website/images/twtter.webp') }}"
                                            alt="icon"></a>
                                </li>
                            @endif
                            @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'linkedin_status') == 'active')
                                <li><a
                                        href="{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'linkedin_link') }}"><img
                                            class="img-fluid w-100" src="{{ asset('website/images/linkdin.webp') }}"
                                            alt="icon"></a>
                                </li>
                            @endif
                            @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'pinterest_status') == 'active')
                                <li><a
                                        href="{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'pinterest_link') }}"><img
                                            class="img-fluid w-100" src="{{ asset('website/images/pintarest.webp') }}"
                                            alt="icon"></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'useful_pages_status') ==
                        'active')
                    <div class="col-sm-6 col-lg-4 col-xl-2 wow fadeInUp">
                        <div class="wsus__footer_menu">
                            <h5>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'useful_pages_title') }}
                            </h5>
                            <ul>
                                @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_ONE->value) as $menu)
                                    <li><a
                                            href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                            {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                    </li>
                                @empty
                                    <li>{{ __('No Links Added') }}</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @endif
                @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'help_center_status') ==
                        'active')
                    <div class="col-sm-6 col-lg-4 col-xl-2 wow fadeInUp">
                        <div class="wsus__footer_menu">
                            <h5>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'help_center_title') }}
                            </h5>
                            <ul>
                                @if (auth()->check() && auth()->user()?->vendor)
                                    @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_VENDOR->value) as $menu)
                                        <li><a
                                                href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                                {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                        </li>
                                    @empty
                                        <li>{{ __('No Links Added') }}</li>
                                    @endforelse
                                @elseif (auth()->check())
                                    @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_USER->value) as $menu)
                                        <li><a
                                                href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                                {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                        </li>
                                    @empty
                                        <li>{{ __('No Links Added') }}</li>
                                    @endforelse
                                @else
                                    @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_TWO->value) as $menu)
                                        <li><a
                                                href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                                {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                        </li>
                                    @empty
                                        <li>{{ __('No Links Added') }}</li>
                                    @endforelse
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp">
                    <div class="wsus__footer_address">
                        <h5>{{ __('Contacts') }}</h5>
                        <p>{{ __('Got Questions? Call us') }}</p>
                        <a class="number"
                            href="callto:67041390762">{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'contact_number') }}</a>
                        <ul>
                            <li>
                                <a
                                    href="mailto::{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'contact_email') }}">
                                    <span><img class="img-fluid w-100" src="{{ asset('website/images/mail.webp') }}"
                                            alt="icon"></span>
                                    {{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'contact_email') }}
                                </a>
                            </li>
                            <li>
                                <b>
                                    <span><img class="img-fluid w-100"
                                            src="{{ asset('website/images/location_1.webp') }}" alt="icon"></span>
                                    {{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'address') }}
                                </b>
                            </li>
                        </ul>
                    </div>
                </div>
                @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'google_store_status') ==
                        'active' ||
                        sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'apple_store_status') ==
                            'active')
                    <div class="col-md-6 col-lg-4 col-xl-2 wow fadeInUp">
                        <div class="wsus__footer_app_download">
                            <h5>{{ __('Download') }} :</h5>
                            <ul>
                                @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'apple_store_status') ==
                                        'active')
                                    <li>
                                        <a href="{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'apple_store_link') }}"
                                            target="_blank">
                                            <span><img class="img-fluid w-100"
                                                    src="{{ asset('website/images/app_store.webp') }}"
                                                    alt="apps"></span>
                                            <div class="text">
                                                <p>{{ __('Download on the') }}</p>
                                                <b>{{ __('App Store') }}</b>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'google_store_status') ==
                                        'active')
                                    <li>
                                        <a
                                            href="{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'google_store_link') }}">
                                            <span>
                                                <img class="img-fluid w-100"
                                                    src="{{ asset('website/images/play_store.webp') }}" alt="apps">
                                            </span>
                                            <div class="text">
                                                <p>{{ __('Download on the') }}</p>
                                                <b>{{ __('Play Store') }}</b>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="wsus__copyright">
                        <p>
                            <a href="{{ route('website.home') }}">{{ $setting->copyright_text }}</a>
                        </p>
                        <ul>
                            <li>
                                <a href="javascript:;" role="button" rel="nofollow">
                                    <img class="img-fluid w-100"
                                        src="{{ asset(sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'payment_gateway_image')) }}"
                                        alt="payment" />
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--============================
        FOOTER 3 END
    ==============================-->
@endif
