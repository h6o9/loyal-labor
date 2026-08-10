@extends('website.layouts.app')

@section('title')
    {{ __('Checkout') }} || {{ $setting->app_name }}
@endsection

@section('content')
    {{-- <!--============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('components::breadcrumb', ['title' => __('Checkout'), 'image' => 'checkout'])
    {{-- <!--============================
        BREADCRUMBS END
    ==============================--> --}}

    {{-- <!--============================
        CHECKOUT START
    =============================--> --}}
    <section class="wsus__checkout mt_120 xs_mt_100 mb_120 xs_mb_100">
        <div class="container">
            <div class="wsus__login_notice mb_40">
                <div class="form-check">
                    <input class="form-check-input" id="create_account_checkbox" name="create_account" form="payment_form"
                        type="checkbox" value="1" @checked(old('create_account'))>
                    <label class="form-check-label" for="create_account_checkbox">
                        {{ __('Want to create an account with your order data?') }}
                        <br>
                        <small>
                            {{ __('Login now to save your information and track your order from your account.') }}
                            <a data-bs-toggle="modal" data-bs-target="#loginModal"
                                href="#">{{ __('Click here to login') }}</a>
                        </small>
                    </label>
                </div>

                <div class="mt-3 @if (!old('create_account')) d-none @endif" id="account_password_wrapper">
                    <label class="form-label" for="account_password">{{ __('Create Password') }} <span
                            class="text-danger">*</span></label>
                    <input class="form-control" id="account_password" name="account_password" form="payment_form"
                        type="password" placeholder="{{ __('Enter a secure password') }}">
                    @error('account_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="wsus__checkout_area">
                <div class="row">
                    <div class="col-lg-6 col-xl-8 wow fadeInUp">
                        <form class="wsus__checkout_form" id="payment_form" action="{{ route('website.place.order') }}"
                            method="POST">
                            @csrf
                            <input name="guest_checkout" type="hidden" value="1">
                            <input name="same_as_shipping" type="hidden" value="1">
                            <h4>{{ __('Order Details') }}</h4>
                            <div class="row" id="billing_details">
                                <div class="col-md-12">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Name') }}</label>
                                        <input name="name" type="text"
                                            value="{{ old('name', auth()->user()?->name) }}" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Email Address') }}</label>
                                        <input name="email" type="email"
                                            value="{{ old('email', auth()->user()?->email) }}" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Phone') }}*</label>
                                        <input name="phone" type="text" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label for="shipping_country">{{ __('Country') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_2" id="shipping_country" name="country_id">
                                            <option value="">{{ __('Select Country') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label for="shipping_state">{{ __('State') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_2" id="shipping_state" name="state_id">
                                            <option value="">{{ __('Select State') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label for="shipping_city">{{ __('City') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_2" id="shipping_city" name="city_id">
                                            <option value="">{{ __('Select City') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Zip / Postal Code') }}</label>
                                        <input name="zip" type="text" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Street address') }}</label>
                                        <input name="address" type="text" placeholder="House number and street name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Note') }}</label>
                                        <textarea name="note" rows="6" placeholder="Write Something"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Select Payment Method') }}</label>
                                    </div>
                                    <div class="wsus__payment_method">
                                        <div class="row">
                                            @forelse (getPaymentMethodsDetails() as $key => $payment)
                                                @if ($payment->status)
                                                    <div class="col-6 col-sm-3">
                                                        <div class="form-group payment_method">
                                                            <label for="payment_method_{{ $key }}"
                                                                @if (!$payment->isCurrencySupported) title="{{ str($payment->name)->title() . ' ' }}{{ __('does not support the currency:') . ' ' }}{{ getSessionCurrency() }}"
                                                                @elseif($key == 'hand_cash' && !$codAvailable) title="{{ __('Some of the cart item does not allow cash on delivery') }}" @endif>
                                                                <input id="payment_method_{{ $key }}"
                                                                    name="payment_method" form="payment_form"
                                                                    type="radio" value="{{ $key }}"
                                                                    @disabled(!$payment->isCurrencySupported || ($key == 'hand_cash' && !$codAvailable))>
                                                                <img class="img-fluid" src="{{ $payment->logo }}"
                                                                    alt="{{ str($payment->name)->title() }}">
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @empty
                                                <div class="col-md-12">
                                                    <div class="form-group payment_method">
                                                        <p>{{ __('No payment method available') }}</p>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <ul class="wsus__checkout_form_btn">
                                        <li><a class="common_btn"
                                                href="{{ route('website.cart') }}">{{ __('Cart List') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 col-xl-4 wow fadeInUp">
                        <div class="wsus__billing_summary">
                            <div class="billing_summary">
                                <h4>{{ __('Billing Summery') }}</h4>
                                <ul class="wsus__billing_product" id="wsus__billing_products">
                                    @php
                                        $cartGroupBy = collect($cart_contents ?? [])->groupBy('vendor_name');
                                    @endphp
                                    @foreach ($cartGroupBy as $name => $carts)
                                        <li class="d-flex justify-content-between">
                                            <h4 class="w-100">
                                                {{ $name }}
                                            </h4>
                                        </li>
                                        @foreach ($carts as $cart)
                                            <li>
                                                <a class="img" href="{{ route('website.product', $cart['slug']) }}">
                                                    <img class="img-fluid w-100" src="{{ asset($cart['image']) }}"
                                                        alt="{{ $cart['name'] }}">
                                                </a>
                                                <div class="text">
                                                    <a href="{{ route('website.product', $cart['slug']) }}">{{ $cart['name'] }}
                                                        <span>({{ $cart['sku'] }})</span>
                                                    </a>
                                                    <h6>{{ currency($cart['price']) }} x {{ $cart['qty'] }}</h6>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                                <div class="wsus__total_price border-0">
                                    <h3>{{ __('Sub Total') }} <span>{{ currency(WsusCart::getCartSubTotal()) }}</span>
                                    </h3>
                                    <div id="billing_summary">
                                        @include('website.cart.price_calculation', [
                                            'cart_contents' => $cart_contents,
                                            'subTotal' => WsusCart::getCartSubTotal(),
                                            'tax' => WsusCart::getCartTotalTax(),
                                            'discount' => WsusCart::getCouponDiscount(),
                                            'total' => WsusCart::getCartTotal(),
                                        ])
                                    </div>
                                </div>
                            </div>

                            <div class="wsus__shipping_method select_shipping_method">
                                <h3>{{ __('Select Shipping Method') }}</h3>
                                <ul>
                                    @forelse ($shippingRules as $shipping)
                                        <li class="m-2">
                                            <div class="form-check">
                                                <input class="form-check-input" id="shipping_{{ $shipping->id }}"
                                                    name="shipping" form="payment_form" type="radio"
                                                    value="{{ $shipping->id }}" @checked($loop->first)>
                                                <label class="form-check-label"
                                                    for="shipping_{{ $shipping->id }}">{{ $shipping->name }} -
                                                    {{ session()->has('coupon_code') && session()->has('free_shipping') && session()->get('free_shipping') ? currency(0) : currency($shipping->price) }}</label>
                                            </div>
                                        </li>
                                    @empty
                                        <li>
                                            {{ __('No shipping method available') }}
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                            <ul class="wsus__checkout_form_btn">
                                <li><button class="common_btn disabled procced_payment" form="payment_form"
                                        type="submit" disabled>{{ __('Payment') }}</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <!--============================
        CHECKOUT END
    =============================--> --}}
    @include('components::preloader')
@endsection

@push('scripts')
    <script>
        "use strict";

        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('create_account_checkbox');
            const passwordWrapper = document.getElementById('account_password_wrapper');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordWrapper.classList.remove('d-none');
                } else {
                    passwordWrapper.classList.add('d-none');
                }
            });
        });

        $(document).ready(function() {
            "use strict";

            function disablePaymentButton(type = '') {
                if (type == 'active') {
                    $('.procced_payment').prop('disabled', false).removeClass('disabled');
                } else {
                    $('.procced_payment').prop('disabled', true).addClass('disabled');
                }
            }

            function uncheckChecked(type) {
                $('input[name="' + type + '"]:checked').prop('checked', false);
            }

            function updateCheckoutSummary(paymentMethod, shippingMethod, type = '') {
                const countryId = $('[name="country_id"]').val();
                const stateId = $('[name="state_id"]').val();
                const cityId = $('[name="city_id"]').val();

                $.ajax({
                    url: "{{ route('website.get.guest.checkout.summary') }}",
                    type: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    data: {
                        _token: '{{ csrf_token() }}',
                        method: paymentMethod,
                        shipping: shippingMethod,
                        country_id: countryId,
                        state_id: stateId,
                        city_id: cityId,
                    },
                    success: function(response) {
                        $('#wsus__billing_products').html(response.products);
                        $('#billing_summary').html(response.calculation);

                        disablePaymentButton('active');
                    },
                    error: function(xhr, status, error) {
                        disablePaymentButton();

                        if (type) {
                            uncheckChecked(type);
                        }

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, messages) {
                                    toastr.error(messages[0]);
                                });
                            } else if (xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error("{{ __('Something went wrong') }}");
                            }
                        } else {
                            toastr.error("{{ __('Unexpected server error') }}");
                        }
                    }
                });
            }

            function handleChange(type) {
                const paymentMethod = $('input[name="payment_method"]:checked').val();
                const shippingMethod = $('input[name="shipping"]:checked').val();

                if (type === 'payment_method' && !shippingMethod) {
                    toastr.error("{{ __('Please select a shipping method') }}");
                    return;
                }

                if (type === 'shipping' && !paymentMethod) {
                    toastr.error("{{ __('Please select a payment method') }}");
                    return;
                }

                updateCheckoutSummary(paymentMethod, shippingMethod, type);
            }

            // Watch for changes
            $('input[name="payment_method"]').on('change', function() {
                handleChange('payment_method');
            });

            $('input[name="shipping"]').on('change', function() {
                handleChange('shipping');
            });

            function onLocationChangeRun() {
                let paymentMethod = $('input[name="payment_method"]:checked').val();
                let shippingMethod = $('input[name="shipping"]:checked').val();

                let countryId = $('[name="country_id"]').val();
                let stateId = $('[name="state_id"]').val();
                let cityId = $('[name="city_id"]').val();

                if (!countryId || !stateId || !cityId) {
                    return;
                }

                disablePaymentButton();

                if (paymentMethod && shippingMethod && countryId && stateId && cityId) {
                    handleChange('address');
                }
            }

            $('select[name="city_id"]')
                .on('change', function() {
                    onLocationChangeRun();
                });

            $(document).on('change', '[name="country_id"]', function() {
                var country_id = $(this).val();
                var form = 'payment_form';

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
                        disablePaymentButton();
                    }
                });
            });

            $(document).on('change', '[name="state_id"]', function() {
                var state_id = $(this).val();
                var form = 'payment_form';
                $.ajax({
                    url: `{{ route('website.get.all.cities.by.state', ':state_id') }}`.replace(
                        ':state_id', state_id),

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
                        disablePaymentButton();
                    }
                });
            });
        });
    </script>
@endpush
