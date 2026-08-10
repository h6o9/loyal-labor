@if (isset($footer_section) && $footer_section->footer_section->status == 1)
    <!--============================
        FOOTER 2 START
    ==============================-->
    <footer class="wsus__footer_2 pt_120 xs_pt_100"
        style="background: url({{ asset('website/images/footer_2_bg.webp') }});">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp">
                    <div class="wsus__footer_2_address">
                        <a class="logo" href="{{ route('website.home') }}">
                            <img class="img-fluid w-100" src="{{ asset($setting->logo_dark) }}" alt="logo">
                        </a>
                        <p>{{ __('Got Questions? Call us') }}</p>
                        <a class="number"
                            href="callto:{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'contact_number') }}">{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'contact_number') }}</a>
                        <ul>
                            <li>
                                <a href="mailto::PureCartexample@gmail.com">
                                    <span>
                                        <img class="img-fluid w-100" src="{{ asset('website/images/mail.webp') }}"
                                            alt="icon">
                                    </span>
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
                <div class="col-sm-6  col-lg-3 col-xl-2 wow fadeInUp">
                    <div class="wsus__footer_2_menu">
                        <h4>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'shop_pages_title') }}
                        </h4>
                        <ul>
                            @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_ONE->value) as $menu)
                                <li><a href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                        {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                </li>
                            @empty
                                <li>{{ __('No Links Added') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2 wow fadeInUp">
                    <div class="wsus__footer_2_menu">
                        <h4>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'help_center_title') }}
                        </h4>
                        <ul>
                            @if (auth()->check() && auth()->user()?->vendor)
                                @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_VENDOR->value) as $menu)
                                    <li><a href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                            {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                    </li>
                                @empty
                                    <li>{{ __('No Links Added') }}</li>
                                @endforelse
                            @elseif (auth()->check())
                                @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_USER->value) as $menu)
                                    <li><a href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                            {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                    </li>
                                @empty
                                    <li>{{ __('No Links Added') }}</li>
                                @endforelse
                            @else
                                @forelse (menuGetBySlug($defaultMenus::FOOTER_MENU_TWO->value) as $menu)
                                    <li><a href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                            {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>{{ $menu['label'] }}</a>
                                    </li>
                                @empty
                                    <li>{{ __('No Links Added') }}</li>
                                @endforelse
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-4 wow fadeInUp">

                    <div class="wsus__footer_2_newsletter">
                        @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'newsletter_status') ==
                                'active')
                            <h4>{{ __('Newsletters') }}</h4>
                            <p>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'newsletter_subtitle') }}
                            </p>
                            <form class="newsletter_form" action="{{ route('newsletter-request') }}" method="post">
                                @csrf
                                <input name="email" type="text" placeholder="{{ __('Enter your Email') }}...">
                                <button class="common_btn" type="submit">{{ __('Subscribe') }}</button>
                            </form>
                        @endif
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
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="wsus__copyright_2">
                        <p><a href="{{ route('website.home') }}">{{ $setting->copyright_text }}</p>
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
        FOOTER 2 END
    ==============================-->
@endif
