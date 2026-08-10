@extends('auth.layout')

@section('title', __('Reset Password'))

@section('content')
    @php
        $loginSection = getSection('login_page', false);
    @endphp
    <!--============================ RAGISTRATION PAGE START ==============================-->
    <section class="wsus__login wsus__registration">
        <div class="row align-items-center">
            <div class="col-lg-5 col-xl-5">
                <div class="wsus__login_img">
                    <img class="img-fluid w-100"
                        src="{{ asset(sectionData(sections: $loginSection, sectionName: 'login_page', propertyName: 'login_image')) }}"
                        alt="reset-password">
                    <a class="logo" href="{{ route('website.home') }}">
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData(sections: $loginSection, sectionName: 'login_page', propertyName: 'logo_image')) }}"
                            alt="logo">
                    </a>
                </div>
            </div>
            <div class="col-lg-7 col-xl-7 wow fadeInUp">
                <div class="wsus__login_right">
                    <div class="wsus__login_area">
                        <h2>{{ __('Reset Password') }}<span>!</span></h2>
                        <p>{{ __('Already have an account') }}? <a
                                href="{{ route('login') }}">{{ __('Log in') }}</a></p>
                        <form action="{{ route('reset-password-store', ['token' => $token]) }}" method="POST">
                            @csrf
                            <div class="wsus__login_input">
                                <label>{{ __('Your email') }}<span class="text-danger">*</span></label>
                                <input name="email" type="email" value="{{ old('email') }}"
                                    placeholder="{{ __('Your email') }}">
                            </div>

                            <div class="wsus__login_input">
                                <label for="password">{{ __('Password') }}<span
                                        class="text-danger">*</span></label>
                                <input id="password" name="password" type="password" placeholder="{{ __('Password') }}">
                            </div>

                            <div class="wsus__login_input">
                                <label for="password_confirm">{{ __('Confirm Password') }}<span
                                        class="text-danger">*</span></label>
                                <input id="password_confirm" name="password_confirmation" type="password"
                                    placeholder="{{ __('Confirm Password') }}">
                            </div>

                            <div class="wsus__login_btn">
                                <input class="recaptcha-token" name="recaptcha_token" type="hidden">
                                <button id="reset-button" type="submit" @class([
                                    'common_btn',
                                    'g-recaptcha-btn' => $setting->recaptcha_status == 'active',
                                ])
                                    @if ($setting->recaptcha_status == 'active') data-sitekey="{{ $setting->recaptcha_site_key }}"
                                                data-action='submit' @endif>{{ __('Sign up') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <a class="back_home" href="{{ route('website.home') }}">{{ __('Back to Home') }}</a>
    </section>
    <!--============================ RAGISTRATION PAGE END ==============================-->

@endsection
