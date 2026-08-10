@extends('website.layouts.app')

@section('title')
    {{ __('Contact Us') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <!--============================ BREADCRUMBS START ==============================-->
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Contact Us') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Contact Us') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================ BREADCRUMBS END ==============================-->
    @php
        $contactPage = $sections;
    @endphp
    <section class="wsus__contact_us mt_95 xs_mt_75">
        <div class="container">
            @if (sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'contact_info_status') ==
                    'active')
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-lg-4 wow fadeInUp">
                        <div class="wsus__contact_info">
                            <div class="icon">
                                <img class="img-fluid" src="{{ asset('website') }}/images/contact_icon_1.png"
                                    alt="contact">
                            </div>
                            <h4>{{ __('Office Address') }}</h4>
                            <p>{{ sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'contact_office_address') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-lg-4 wow fadeInUp">
                        <div class="wsus__contact_info">
                            <div class="icon">
                                <img class="img-fluid" src="{{ asset('website') }}/images/contact_icon_2.png"
                                    alt="contact">
                            </div>
                            <h4>{{ __('Send a Message') }}</h4>
                            <a
                                href="mailto:{{ sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'contact_us_email') }}">{{ sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'contact_us_email') }}</a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-lg-4 wow fadeInUp">
                        <div class="wsus__contact_info">
                            <div class="icon">
                                <img class="img-fluid" src="{{ asset('website') }}/images/contact_icon_3.png"
                                    alt="contact">
                            </div>
                            <h4>{{ __('Let\'s Discuss') }}</h4>
                            <a
                                href="callto:1234567890">{{ sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'contact_us_phone') }}</a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-lg-4 wow fadeInUp">
                        <div class="wsus__contact_info">
                            <div class="icon">
                                <img class="img-fluid" src="{{ asset('website') }}/images/contact_icon_4.png"
                                    alt="contact">
                            </div>
                            <h4>{{ __('Team Up with Us') }}</h4>
                            <p>{{ sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'tema_up_message') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            @if (sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'contact_us_form_status') ==
                    'active')
                <div class="wsus__contact_form_area mt_30 wow fadeInUp">
                    <div class="row align-items-center">
                        <div class="col-xl-4 col-lg-5 d-md-none d-lg-block">
                            <div class="wsus__contact_form_img">
                                <img class="img-fluid"
                                    src="{{ asset(sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'contact_image')) }}"
                                    alt="contact">
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-7">
                            <form id="contactForm" class="wsus__contact_form" action="{{ route('website.send-contact-message') }}"
                                method="POST">
                                @csrf
                                <!-- Hidden field set only by JavaScript -->
                                <input type="hidden" id="js_enabled_field" name="js_enabled" value="" class="d-none">
                                <!-- Honeypot field (should remain empty) -->
                                <input type="text" name="website" class="honeypot-field d-none" autocomplete="off">
                                <!-- Time-based field -->
                                <input type="hidden" id="form_start_time" name="form_start_time" value="">
                                <h4>{{ __('Send Us Message') }}</h4>
                                <p>{{ __('Your email address will not be published. Required fields are marked') }} *</p>

                                <div class="row">
                                    <div class="col-xl-6 col-md-6">
                                        <input name="name" type="text" value="{{ old('name') }}"
                                            placeholder="{{ __('Name') }}*" required>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <input name="email" type="email" value="{{ old('email') }}"
                                            placeholder="{{ __('Email') }}*" required>
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <input name="phone" type="text" value="{{ old('phone') }}"
                                            placeholder="{{ __('Phone') }}*">
                                    </div>
                                    <div class="col-xl-6 col-md-6">
                                        <input name="subject" type="text" value="{{ old('subject') }}"
                                            placeholder="{{ __('Subject') }}*" required>
                                    </div>
                                    <div class="col-xl-12">
                                        <textarea name="message" rows="5" placeholder="{{ __('Message') }}*" required>{{ old('message') }}</textarea>
                                        <input class="recaptcha-token" name="recaptcha_token" type="hidden">
                                        <button type="submit" @class([
                                            'common_btn',
                                            'g-recaptcha-btn' => $setting->recaptcha_status == 'active',
                                        ])
                                            @if ($setting->recaptcha_status == 'active') data-sitekey="{{ $setting->recaptcha_site_key }}" data-action='submit' @endif>{{ __('Submit Now') }}</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            @endif
            @if (sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'map_status') == 'active')
                <div class="wsus__contact_map mt_120 xs_mt_100 pb_120 xs_pb_100 wow fadeInUp">
                    <iframe
                        src="{{ sectionData(sections: $contactPage, sectionName: 'contact_us_page', propertyName: 'map_link') }}"
                        style="border:0;" width="600" height="450" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('js')
    <script>
        let formLoadTime;
        $(function() {
            // 1. Record form load time
            formLoadTime = Date.now();
            $('#form_start_time').val(formLoadTime);

            // 2. Set JS-only field
            $('#js_enabled_field').val('true');

            // 3. On form submit — run checks
            $('#contactForm').on('submit', function(e) {
                const currentTime = Date.now();
                const timeDiff = currentTime - formLoadTime;
                const jsEnabled = $('#js_enabled_field').val();
                const honeypotValue = $('input[name="website"]').val();

                if (timeDiff < 5000) {
                    e.preventDefault();
                    alert("Bot activity detected: Submitted too quickly.");
                    return false;
                }

                if (jsEnabled !== 'true') {
                    e.preventDefault();
                    alert("Bot activity detected: JavaScript check failed.");
                    return false;
                }

                if (honeypotValue.trim() !== "") {
                    e.preventDefault();
                    alert("Bot activity detected: Hidden field should be empty.");
                    return false;
                }

                // All checks passed — allow form to submit
            });
        });
    </script>
@endpush
