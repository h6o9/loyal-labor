@if (isset($top_header_section) && $top_header_section->top_header_section->status == 1)
    <!--===========================
        TOPBAR START
    ============================-->
    <section class="wsus__topbar">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-4 col-lg-7 col-md-8 d-none d-md-block">
                    <ul class="wsus__topbar_left d-flex flex-wrap">
                        <li>
                            <a href="javascript:;" role="button" rel="nofollow" @style('cursor: default;')>
                                <span><img class="img-fluid w-100" src="{{ asset('website/images/location_1.webp') }}"
                                        alt="icon"></span>
                                {{ sectionData(sections: $top_header_section, sectionName: 'top_header_section', propertyName: 'address') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('website.track.order') }}">
                                <span><img class="img-fluid w-100" src="{{ asset('website/images/track.webp') }}"
                                        alt="icon"></span>
                                {{ __('Order Tracking') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-5 d-none d-xl-block">
                    @if (sectionData(sections: $top_header_section, sectionName: 'top_header_section', propertyName: 'offer_status') ==
                            'active')
                        <div class="wsus__topbar_countdown">
                            <div class="simply-countdown top-offer-countdown"
                                data-start-date="{{ sectionData(sections: $top_header_section, sectionName: 'top_header_section', propertyName: 'offer_start_time') }}"
                                data-end-date="{{ sectionData(sections: $top_header_section, sectionName: 'top_header_section', propertyName: 'offer_end_time') }}">
                            </div>
                            <p>{{ sectionData(sections: $top_header_section, sectionName: 'top_header_section', propertyName: 'title') }}
                            </p>
                            <a
                                href="{{ sectionData(sections: $top_header_section, sectionName: 'top_header_section', propertyName: 'offer_link') }}">{{ sectionData(sections: $top_header_section, sectionName: 'top_header_section', propertyName: 'offer_link_text') }}</a>
                        </div>
                    @endif
                </div>
                <div class="col-xl-3 col-lg-5 col-md-4">
                    <ul class="wsus__topbar_right d-flex flex-wrap">
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
                </div>
            </div>
        </div>
    </section>
    <!--===========================
    TOPBAR END
============================-->
@endif
