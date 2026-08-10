@if (isset($footer_section) && $footer_section->footer_section->status == 1)
    <!--============================
        FOOTER 3 START
    ==============================-->
    <footer class="wsus__footer_4 pt_120 xs_pt_100">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xl-3 wow fadeInUp">
                    <div class="wsus__footer_4_social_media">
                        <a class="logo" href="{{ route('website.home') }}">
                            <img class="img-fluid w-100" src="{{ asset($setting->logo_dark) }}" alt="logo">
                        </a>
                        <p>{{ __('For further information, please reach Us at') }}
                            :
                            {{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'contact_email') }}
                        </p>
                        <h6>{{ __('Social Media') }}:</h6>
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
                <div class="col-sm-6 col-xl-2 wow fadeInUp">
                    <div class="wsus__footer_4_menu">
                        <h5>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'hot_categories_title') }}
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
                <div class="col-sm-6 col-xl-3 wow fadeInUp">
                    <div class="wsus__footer_4_timeline">
                        <h5>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'help_center_title') }}
                        </h5>
                        <p>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'address') }}
                        </p>
                        <ul>
                            @php
                                $shop_time = sectionData(
                                    sections: $footer_section,
                                    sectionName: 'footer_section',
                                    propertyName: 'shop_time',
                                );

                                $shop_time = $shop_time ? encodeShopTimeToHtml($shop_time) : '';
                            @endphp

                            {!! $shop_time !!}
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 wow fadeInUp">
                    @if (sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'newsletter_status') ==
                            'active')
                        <div class="wsus__footer_4_newsletter">
                            <h5>{{ __('Newsletters') }}</h5>
                            <p>{{ sectionData(sections: $footer_section, sectionName: 'footer_section', propertyName: 'newsletter_subtitle') }}
                            </p>
                            <form class="newsletter_form" action="{{ route('newsletter-request') }}" method="post">
                                @csrf
                                <div class="subscrib">
                                    <input name="email" type="text" placeholder="{{ __('Enter your Email') }}...">
                                    <button class="common_btn" type="submit">{{ __('Subscribe') }}<i
                                            class="far fa-angle-right"></i></button>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="defaultCheck1" name="terms" type="checkbox"
                                        value="1" required>
                                    <label class="form-check-label" for="defaultCheck1">
                                        {{ __('I accept the terms of service and privacy policy') }}
                                    </label>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="wsus__copyright_4">
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
        FOOTER 3 END
    ==============================-->
@endif
