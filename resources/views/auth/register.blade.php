@extends('auth.layout')

@section('title', __('Registration'))

@section('content')
    @php
        $section = getSection('register_page', false);
    @endphp
    <!--============================ RAGISTRATION PAGE START ==============================-->
    <section class="wsus__login wsus__registration">
        <div class="row align-items-center">
            <div class="col-lg-5 col-xl-5">
                <div class="wsus__login_img">
                    <img class="img-fluid w-100"
                        src="{{ asset(sectionData(sections: $section, sectionName: 'register_page', propertyName: 'register_image')) }}"
                        alt="registration">
                    <a class="logo" href="{{ route('website.home') }}">
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData(sections: $section, sectionName: 'register_page', propertyName: 'logo_image')) }}"
                            alt="logo">
                    </a>
                </div>
            </div>
            <div class="col-lg-7 col-xl-7 wow fadeInUp">
                @if ($section->register_page->status)
                    <div class="wsus__login_right">
                        <div class="wsus__login_area">
                            <h2>{{ __('Sign Up') }}<span>!</span></h2>
                            <p>{{ __('Already have an account') }}? <a
                                    href="{{ route('login') }}">{{ __('Log in') }}</a></p>
                            <form id="registerForm" action="{{ route('register') }}" method="POST">
                                @csrf
                                <!-- Hidden field set only by JavaScript -->
                                <input type="hidden" id="js_enabled_field" name="js_enabled" value="" class="d-none">
                                <!-- Honeypot field (should remain empty) -->
                                <input type="text" name="website" class="honeypot-field d-none" autocomplete="off">
                                <!-- Time-based field -->
                                <input type="hidden" id="form_start_time" name="form_start_time" value="">

                                <div class="wsus__login_input">
                                    <label for="name">{{ __('Name') }}<span class="text-danger">*</span></label>
                                    <input name="name" type="text" value="{{ old('name') }}"
                                        placeholder="{{ __('Your Name') }}">
                                </div>

                                <div class="wsus__login_input">
                                    <label>{{ __('Your email') }}<span class="text-danger">*</span></label>
                                    <input name="email" type="email" value="{{ old('email') }}"
                                        placeholder="{{ __('Your email') }}">
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="wsus__login_input">
                                            <label for="password">{{ __('Password') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="password" name="password" type="password"
                                                placeholder="{{ __('Password') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="wsus__login_input">
                                            <label for="password_confirm">{{ __('Confirm Password') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="password_confirm" name="password_confirmation" type="password"
                                                placeholder="{{ __('Confirm Password') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="wsus__login_input">
                                    <div class="form-check">
                                        <input class="form-check-input" id="termsCheck" name="tos" type="checkbox"
                                            value="yes" required>
                                        <label class="form-check-label" for="termsCheck">
                                            {{ __('By clicking Create account, I agree that I have read and accepted the') }}
                                            <a
                                                href="{{ route('website.terms.and.conditions') }}">{{ __('Terms of Use') }}</a>
                                            {{ __('and') }} <a
                                                href="{{ route('website.privacy.policy') }}">{{ __('Privacy Policy.') }}</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="wsus__login_btn">
                                    <input class="recaptcha-token" name="recaptcha_token" type="hidden">
                                    <button id="login-button" type="submit" @class([
                                        'common_btn',
                                        'g-recaptcha-btn' => $setting->recaptcha_status == 'active',
                                    ])
                                        @if ($setting->recaptcha_status == 'active') data-sitekey="{{ $setting->recaptcha_site_key }}"
                                                data-action='submit' @endif>{{ __('Sign up') }}</button>
                                </div>
                            </form>
                        </div>
                        @if (sectionData(sections: $section, sectionName: 'register_page', propertyName: 'social_login_status') == 'active')
                            @if ($setting->google_login_status == 'active' && $setting->google_client_id)
                                <div class="wsus__login_another_option">
                                    <p>{{ __('OR') }}</p>
                                    <a href="{{ route('auth.social', ['driver' => 'google']) }}">
                                        <span><img class="img-fluid w-100" src="{{ asset('website/images/google.webp') }}"
                                                alt="google"></span>
                                        {{ __('Sign up with Google') }}
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <a class="back_home" href="{{ route('website.home') }}">{{ __('Back to Home') }}</a>
    </section>
    <!--============================ RAGISTRATION PAGE END ==============================-->

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
            $('#registerForm').on('submit', function(e) {
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
