<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input class="block mt-1 w-full" id="password" name="password" type="password" required
                autocomplete="current-password" />

            <x-input-error class="mt-2" :messages="$errors->get('password')" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
        <title>{{ __('Registration') }} - {{ $setting->app_name }}</title>
        <link type="image/png" href="{{ asset('website/images/favicon.webp') }}" rel="icon">
        @include('website.layouts.css.styles')
        <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
        @include('website.layouts.js.3rd-party-api', ['setting' => $setting])
    </head>

    <body>
        <!--============================
        RAGISTRATION PAGE START
    ==============================-->
        <section class="wsus__login wsus__registration">
            <div class="row align-items-center">
                <div class="col-lg-5 col-xl-5">
                    <div class="wsus__login_img">
                        <img class="img-fluid w-100" src="{{ asset('website/images/signup_img.webp') }}"
                            alt="registration">
                        <a class="logo" href="{{ route('website.home') }}">
                            <img class="img-fluid w-100" src="{{ asset('website/images/logo_3.webp') }}" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 col-xl-7 wow fadeInUp">
                    <div class="wsus__login_right">
                        <div class="wsus__login_area">
                            <h2>Sign Up<span>!</span></h2>
                            <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                            <form action="#">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="wsus__login_input">
                                            <label>First name*</label>
                                            <input type="text">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="wsus__login_input">
                                            <label>Last name</label>
                                            <input type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="wsus__login_input">
                                    <label>Your email*</label>
                                    <input type="email">
                                </div>
                                <div class="wsus__login_input">
                                    <label>Password*</label>
                                    <input type="text">
                                </div>
                                <div class="wsus__login_input">
                                    <div class="form-check">
                                        <input class="form-check-input" id="flexCheckDefault" type="checkbox"
                                            value="">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            By clicking Create account, I agree that I have read and accepted the <a
                                                href="#">Terms of Use</a> and <a href="#">Privacy
                                                Policy.</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="wsus__login_btn">
                                    <a class="common_btn" href="#">Sign up</a>
                                </div>
                            </form>
                        </div>
                        <div class="wsus__login_another_option">
                            <p>OR</p>
                            <a href="#">
                                <span><img class="img-fluid w-100" src="{{ asset('website/images/google.webp') }}"
                                        alt="google"></span>
                                Signin with Google
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <a class="back_home" href="{{ route('website.home') }}">Back to Home</a>
        </section>
        <!--============================
        RAGISTRATION PAGE END
    ==============================-->

        @include('website.layouts.js.scripts')
    </body>

</html>
