@extends('auth.layout')

@section('title', __('Forgot Password'))

@section('content')

    <!--============================
                                FORGOT PASSWORD START
                            ==============================-->
    <section class="wsus__login wsus__forgot_password">
        <div class="row align-items-center">
            <div class="col-lg-5 col-xl-5">
                <div class="wsus__login_img">
                    <img class="img-fluid w-100" src="{{ asset('website/images/forgot_pass_img.webp') }}"
                        alt="forgot Password">
                    <a class="logo" href="{{ route('website.home') }}">
                        <img class="img-fluid w-100" src="{{ asset('website/images/logo_3.webp') }}" alt="logo">
                    </a>
                </div>
            </div>
            <div class="col-lg-7 col-xl-7 wow fadeInUp">
                <div class="wsus__login_right">
                    <div class="wsus__login_area">
                        <h2>{{ __('Forgot Password') }}!</h2>
                        <p>{{ __('Enter your email and get Password') }}</p>
                        <form id="forgotPasswordForm" action="{{ route('forget-password') }}" method="POST">
                            @csrf
                            <!-- Hidden field set only by JavaScript -->
                            <input type="hidden" id="js_enabled_field" name="js_enabled" value="" class="d-none">
                            <!-- Honeypot field (should remain empty) -->
                            <input type="text" name="website" class="honeypot-field d-none" autocomplete="off">
                            <!-- Time-based field -->
                            <input type="hidden" id="form_start_time" name="form_start_time" value="">
                            <div class="wsus__login_input">
                                <label>{{ __('Email or Username') }}<span class="text-danger">*</span></label>
                                <input name="email" type="email" value="{{ old('email') }}"
                                    placeholder="{{ __('Email or Username') }}">
                            </div>
                            <div class="wsus__login_btn">
                                <input class="recaptcha-token" name="recaptcha_token" type="hidden">
                                <button id="login-button" type="submit" @class([
                                    'common_btn',
                                    'g-recaptcha-btn' => $setting->recaptcha_status == 'active',
                                ])
                                    @if ($setting->recaptcha_status == 'active') data-sitekey="{{ $setting->recaptcha_site_key }}"
                                                data-action='submit' @endif>{{ __('Forgot Password') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <a class="back_home" href="{{ route('website.home') }}">Back to Home</a>
    </section>
    <!--============================
                                FORGOT PASSWORD END
                            ==============================-->

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
            $('#forgotPasswordForm').on('submit', function(e) {
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
