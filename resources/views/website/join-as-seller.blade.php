@extends('website.layouts.app')

@section('title')
    {{ __('Join a seller') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Join a seller') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Join a seller') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="become_vendor_page pt_120 xs_pt_100 mb_120 xs_mb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="become_vendor_page_text wow fadeInLeft">
                        {!! clean($pageData->description ?? __('Join as a Seller')) !!}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="become_vendor_page_form wow fadeInRight">
                        <h2>{{ __('Apply As seller') }}</h2>
                        <form id="vendor_form" action="{{ route('website.join-as-seller.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="wsus__login_form_input">
                                        <label for="name">{{ __('Your Name') }}<span
                                                class="text-danger">*</span></label>
                                        <input id="name" name="name" type="text"
                                            value="{{ auth()->check() ? auth()->user()?->name : old('name') }}"
                                            placeholder="{{ __('Your name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="wsus__login_form_input">
                                        <label for="shop_name">{{ __('Shop Name') }}<span
                                                class="text-danger">*</span></label>
                                        <input id="shop_name" name="shop_name" type="text"
                                            value="{{ old('shop_name') }}" placeholder="{{ __('Your shop name') }}">
                                    </div>
                                </div>
                                @guest
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="phone">{{ __('Your Phome') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="phone" name="phone" type="text"
                                                value="{{ auth()->check() ? auth()->user()?->phone : old('phone') }}"
                                                placeholder="{{ __('Your phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="email">{{ __('Your Email') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="email" name="email" type="email"
                                                value="{{ auth()->check() ? auth()->user()?->email : old('email') }}"
                                                placeholder="{{ __('Your email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="country">{{ __('Country') }}<span class="text-danger">*</span></label>
                                            <select class="select_2" id="country" name="country_id">
                                                <option value="">{{ __('Select Country') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="state">{{ __('State') }}<span class="text-danger">*</span></label>
                                            <select class="select_2" id="state" name="state_id">
                                                <option value="">{{ __('Select State') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="city">{{ __('City') }}<span class="text-danger">*</span></label>
                                            <select class="select_2" id="city" name="city_id">
                                                <option value="">{{ __('Select City') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="zip">{{ __('Zip') }}<span class="text-danger">*</span></label>
                                            <input id="zip" name="zip_code" type="text" value="{{ old('zip') }}"
                                                placeholder="{{ __('Zip Code') }}">
                                        </div>
                                    </div>
                                @endguest
                                <div class="col-xl-12">
                                    <div class="wsus__login_form_input">
                                        <label>{{ __('Shop Address') }}<span class="text-danger">*</span></label>
                                        <textarea name="address" rows="3" placeholder="{{ __('Write shop address') }}"></textarea>
                                    </div>
                                </div>
                                @guest
                                    <div class="col-xl-12">
                                        <div class="wsus__login_form_input">
                                            <label for="bio">{{ __('Bio') }}</label>
                                            <textarea id="bio" name="bio" rows="3" placeholder="{{ __('Bio') }}">{{ auth()->check() ? auth()->user()?->bio : old('bio') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="password">{{ __('Password') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="password" name="password" type="password"
                                                placeholder="{{ __('Password') }}" autocomplete="false">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__login_form_input">
                                            <label for="password_confirm">{{ __('Confirm Password') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="password_confirm" name="password_confirmation" type="password"
                                                placeholder="{{ __('Confirm Password') }}" autocomplete="false">
                                        </div>
                                    </div>
                                @endguest
                                <div class="col-md-12">
                                    <div class="wsus__login_form_input">
                                        <label>{{ __('KYC TYPE') }}<span class="text-danger">*</span></label>
                                        <select class="form-select" id="kyc_type" name="kyc_type">
                                            <option value="">{{ __('Select KYC Document Type') }}</option>
                                            @foreach ($kycType as $type)
                                                <option value="{{ $type->id }}" @selected($type->id == old('kyc_type_id'))>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="wsus__login_form_input">
                                        <label>{{ __('Document') }}<span class="text-danger">*</span></label>
                                        <input name="kyc_file" type="file" placeholder="{{ __('Upload Document') }}">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="wsus__login_form_input m-0">
                                        <div class="form-check">
                                            <input class="form-check-input" id="termsCheck" name="tos"
                                                type="checkbox" value="yes" required>
                                            <label class="form-check-label" for="termsCheck">
                                                {{ __('By clicking Create account, I agree that I have read and accepted the') }}
                                                <a
                                                    href="{{ route('website.terms.and.conditions') }}">{{ __('Terms of Use') }}</a>
                                                {{ __('and') }} <a
                                                    href="{{ route('website.privacy.policy') }}">{{ __('Privacy Policy.') }}</a>
                                            </label>
                                        </div>
                                        <input class="recaptcha-token" name="recaptcha_token" type="hidden">
                                        <button class="common_btn" type="submit" @class([
                                            'common_btn',
                                            'g-recaptcha-btn' => $setting->recaptcha_status == 'active',
                                        ])
                                            @if ($setting->recaptcha_status == 'active') data-sitekey="{{ $setting->recaptcha_site_key }}"
                                                    data-action='submit' @endif>{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        "use strict";

        $(document).ready(function() {
            $(document).on('change', '[name="country_id"]', function() {
                var country_id = $(this).val();
                var form = 'vendor_form';

                $.ajax({
                    url: `{{ route('website.get.all.states.by.country', ':country_id') }}`.replace(
                        ':country_id', country_id),
                    beforeSend: function() {
                        $('.preloader_area').removeClass('d-none');
                        // disable the input
                        $(`#${form} [name="state_id"]`).html(
                            '<option value="" selected disabled>{{ __('Select State') }}</option>'
                        );

                        $(`#${form} [name="state_id"]`).prop('disabled', true);
                    },
                    success: function(response) {
                        $(`#${form} [name="state_id"]`).html('');
                        let options =
                            '<option value="" selected disabled>{{ __('Select State') }}</option>';

                        response.data.forEach(function(state) {
                            options += '<option value="' +
                                state.id +
                                '">' + state.name + '</option>';
                        })
                        $(`#${form} [name="state_id"]`).html(options).niceSelect(
                                'destroy')
                            .niceSelect();
                    },
                    error: function(error) {
                        handleError(error);
                    },
                    complete: function() {
                        $('.preloader_area').addClass('d-none');
                        $(`#${form} [name="state_id"]`).prop('disabled', false);
                    }
                });
            });

            $(document).on('change', '[name="state_id"]', function() {
                var state_id = $(this).val();
                var form = 'vendor_form';
                $.ajax({
                    url: `{{ route('website.get.all.cities.by.state', ':state_id') }}`.replace(
                        ':state_id', state_id),
                    ,
                    beforeSend: function() {
                        $('.preloader_area').removeClass('d-none');
                        $(`#${form} [name="city_id"]`).html(
                            '<option value="" selected disabled>{{ __('Select City') }}</option>'
                        );

                        $(`#${form} [name="city_id"]`).prop('disabled', true);
                    },
                    success: function(response) {
                        $(`#${form} [name="city_id"]`).html('');

                        let options =
                            '<option value="" selected disabled>{{ __('Select City') }}</option>';

                        response.data.forEach(function(city) {
                            options += '<option value="' +
                                city.id +
                                '">' + city.name + '</option>';
                        })
                        $(`#${form} [name="city_id"]`).html(options).niceSelect(
                                'destroy')
                            .niceSelect();
                    },
                    error: function(error) {
                        handleError(error);
                    },
                    complete: function() {
                        $('.preloader_area').addClass('d-none');
                        $(`#${form} [name="city_id"]`).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
