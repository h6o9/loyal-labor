<div class="wsus__login_right">
    <div class="wsus__login_area">
        <h2>{{ __('Sign in') }}<span>!</span></h2>
        <p>{{ __('New User') }} ? <a href="{{ route('register') }}">{{ __('Create an Account') }}</a>
        </p>
        <form action="{{ route('user-login') }}?r={{ request()->path() }}" method="POST">
            @csrf
            <input class="recaptcha-token" name="recaptcha_token" type="hidden">
            <div class="wsus__login_input">
                <label>{{ __('Email or Username') }}<span class="text-dagner">*</span></label>
                <input name="email" type="email" value="{{ old('email') }}"
                    placeholder="{{ __('Email or Username') }}">
            </div>
            <div class="wsus__login_input">
                <label>{{ __('Password') }}<span class="text-dagner">*</span></label>
                <input name="password" type="password" placeholder="{{ __('Password') }}">
                <a class="forgot" href="{{ route('password.request') }}">{{ __('Forgot Password') }}?</a>
            </div>
            <div class="wsus__login_input">
                <div class="form-check">
                    <input class="form-check-input" id="flexCheckDefault" name="remember" type="checkbox" value="on"
                        @checked(old('remember'))>
                    <label class="form-check-label" for="flexCheckDefault">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
            <div class="wsus__login_btn">
                <button id="login-button" type="submit" @class([
                    'common_btn',
                    'g-recaptcha-btn' => $setting->recaptcha_status == 'active',
                ])
                    @if ($setting->recaptcha_status == 'active') data-sitekey="{{ $setting->recaptcha_site_key }}" data-action="submit" @endif>{{ __('Sign In') }}</button>
            </div>
        </form>
    </div>

    @if (isset($loginSection) &&
            sectionData(sections: $loginSection, sectionName: 'login_page', propertyName: 'social_login_status') ==
                'active')
        @if ($setting->google_login_status == 'active' && $setting->google_client_id)
            <div class="wsus__login_another_option">
                <p>{{ __('OR') }}</p>
                <a href="{{ route('auth.social', ['driver' => 'google']) }}">
                    <span><img class="img-fluid w-100" src="{{ asset('website/images/google.webp') }}"
                            alt="google"></span>
                    {{ __('SignIn with Google') }}
                </a>
            </div>
        @endif
    @else
        @if ($setting->google_login_status == 'active' && $setting->google_client_id)
            <div class="wsus__login_another_option">
                <p>{{ __('OR') }}</p>
                <a href="{{ route('auth.social', ['driver' => 'google']) }}">
                    <span><img class="img-fluid w-100" src="{{ asset('website/images/google.webp') }}"
                            alt="google"></span>
                    {{ __('SignIn with Google') }}
                </a>
            </div>
        @endif
    @endif
</div>
