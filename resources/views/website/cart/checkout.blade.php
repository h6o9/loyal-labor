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
            <div class="wsus__shipping_address mb_40">
                <h4 class="d-flex flex-wrap justify-content-between">{{ __('Shipping Address') }}
                    <button class="common_btn" data-bs-toggle="modal" data-bs-target="#addressModal" type="button"><i
                            class="far fa-plus"></i>
                        {{ __('Add New') }}</button>
                </h4>
                <div class="row">
                    @forelse ($addresses as $address)
                        <div class="col-md-6 col-lg-4 col-xl-4 wow fadeInUp">
                            <div class="wsus__shipping_address_item">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="shipping{{ $address->id }}"
                                        name="shipping_address_id" form="payment_form" type="radio"
                                        value="{{ $address->id }}">
                                    <label class="form-check-label"
                                        for="shipping{{ $address->id }}">{{ $address->full_address }}</label>
                                </div>
                                <div class="wsus__shipping_mail_address">
                                    <p>{{ $address->email }}</p>
                                    <p>{{ $address->phone }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <div class="wsus__shipping_address_item">
                                <p class="text-center">{{ __('No shipping address found') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="wsus__checkout_area">
                <div class="row">
                    <div class="col-lg-6 col-xl-8 wow fadeInUp">
                        <form class="wsus__checkout_form" id="payment_form" action="{{ route('website.place.order') }}"
                            method="POST">
                            @csrf
                            <h4>{{ __('Billing Details') }}</h4>
                            <div class="row" id="billing_details">
                                @include('website.cart.billing_details')
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
                                                                    name="payment_method" form="payment_form" type="radio"
                                                                    value="{{ $key }}"
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
                                <li><button class="common_btn disabled procced_payment" form="payment_form" type="submit"
                                        disabled>{{ __('Payment') }}</button>
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

    <div class="wsus__product_modal">
        <div class="modal fade" id="addressModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="btn-close header_close" data-bs-dismiss="modal" type="button"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="wsus__checkout_form" id="address-form"
                            action="{{ route('website.user.address.store') }}" method="POST">
                            @csrf
                            <h4>{{ __('New Address') }}</h4>
                            <div class="row">
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Name') }}<span class="text-danger">*</span></label>
                                        <input name="name" type="text"
                                            value="{{ old('name', auth()->user()?->name) }}"
                                            placeholder="{{ __('Name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Email Address') }}<span class="text-danger">*</span></label>
                                        <input name="email" type="email"
                                            value="{{ old('email', auth()->user()?->email) }}"
                                            placeholder="{{ __('Email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Phone') }}<span class="text-danger">*</span></label>
                                        <input name="phone" type="text"
                                            value="{{ old('phone', auth()->user()?->phone) }}"
                                            placeholder="{{ __('Phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label" for="a_country_id">{{ __('Country Name') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" id="a_country_id" name="a_country_id">
                                            <option value="" selected disabled>{{ __('Select Country') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label" for="a_state_id">{{ __('State Name') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" id="a_state_id" name="a_state_id">
                                            <option value="" selected disabled>{{ __('Select State') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label" for="a_city_id">{{ __('City') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" id="a_city_id" name="a_city_id">
                                            <option value="" selected disabled>{{ __('Select City') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Zip / Postal Code') }}<span class="text-danger">*</span></label>
                                        <input name="zip" type="text" placeholder="Zip Code">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <div class="wsus__checkout_form_input">
                                        <label class="type_label">{{ __('Type') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select_js" name="type">
                                            <option value="">{{ __('Select Type') }}</option>
                                            <option value="home">{{ __('Home') }}</option>
                                            <option value="office">{{ __('Office') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="wsus__checkout_form_input">
                                        <label>{{ __('Street address') }}<span class="text-danger">*</span></label>
                                        <input name="address" type="text"
                                            placeholder="{{ __('House number and street name') }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="common_btn  btn-danger" data-bs-dismiss="modal"
                            type="button">{{ __('Close') }}</button>
                        <button class="common_btn save" form="address-form" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components::preloader')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            "use strict";

            function getShippingData() {
                return $('input[name="shipping_address_id"]:checked').val();
            }

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

            function updateCheckoutSummary(paymentMethod, shippingMethod, shippingData, type = '') {
                $.ajax({
                    url: "{{ route('website.get.checkout.summary') }}",
                    type: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    data: {
                        _token: '{{ csrf_token() }}',
                        method: paymentMethod,
                        shipping: shippingMethod,
                        shipping_address_id: shippingData,
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
                const shippingData = $('input[name="shipping_address_id"]:checked').val();
                const paymentMethod = $('input[name="payment_method"]:checked').val();
                const shippingMethod = $('input[name="shipping"]:checked').val();

                if (!shippingData) {
                    toastr.error("{{ __('Please select valid shipping address') }}");
                    return;
                }

                if (type === 'payment_method' && !shippingMethod) {
                    toastr.error("{{ __('Please select a shipping method') }}");
                    return;
                }

                if (type === 'shipping' && !paymentMethod) {
                    toastr.error("{{ __('Please select a payment method') }}");
                    return;
                }

                updateCheckoutSummary(paymentMethod, shippingMethod, shippingData, type);
            }

            // Watch for changes
            $('input[name="payment_method"]').on('change', function() {
                handleChange('payment_method');
            });

            $('input[name="shipping"]').on('change', function() {
                handleChange('shipping');
            });

            $('input[name="shipping_address_id"]').on('change', function() {
                let paymentMethod = $('input[name="payment_method"]:checked').val();
                let shippingMethod = $('input[name="shipping"]:checked').val();

                if (paymentMethod && shippingMethod) {
                    handleChange('address');
                }
            });

            // manage show hide of billing address fields
            function toggleBillingAddress() {
                if ($('#same_as_shipping').is(':checked')) {
                    $('#billing_address_fields').addClass('d-none');
                } else {
                    $('#billing_address_fields').removeClass('d-none');
                }
            }

            // Initial toggle on page load
            toggleBillingAddress();

            // On checkbox change
            $('#same_as_shipping').on('change', toggleBillingAddress);
        });

        $(document).ready(function() {
            // get states
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
                    }
                });
            });

            $(document).on('change', '[name="a_country_id"]', function() {
                var country_id = $(this).val();
                var form = 'address-form';

                $.ajax({
                    url: `{{ route('website.get.all.states.by.country', ':country_id') }}`.replace(
                        ':country_id', country_id),
                    beforeSend: function() {
                        $('.preloader_area').removeClass('d-none');
                        $(`#${form} [name="a_state_id"]`).prop('disabled', true);
                    },
                    success: function(response) {
                        $(`#${form} [name="a_state_id"]`).html('');
                        let options =
                            '<option value="" selected disabled>{{ __('Select State') }}</option>';

                        response.data.forEach(function(state) {
                            options += '<option value="' +
                                state.id +
                                '">' + state.name + '</option>';
                        });

                        $(`#${form} [name="a_state_id"]`).html(options).niceSelect('destroy')
                            .niceSelect();
                        $(`#${form} [name="a_state_id"]`).prop('disabled', false);

                        $(`#${form} [name="a_state_id"]`).niceSelect('update');
                    },
                    error: function(error) {
                        handleError(error);
                    },
                    complete: function() {
                        $('.preloader_area').addClass('d-none');
                        $(`#${form} [name="a_state_id"]`).prop('disabled', false);
                    }
                });
            });

            $(document).on('change', '[name="a_state_id"]', function() {
                var state_id = $(this).val();
                var form = 'address-form';
                $.ajax({
                    url: `{{ route('website.get.all.cities.by.state', ':state_id') }}`.replace(
                        ':state_id', state_id),
                    beforeSend: function() {
                        $('.preloader_area').removeClass('d-none');
                        $(`#${form} [name="a_city_id"]`).prop('disabled', true);
                    },
                    success: function(response) {
                        $(`#${form} [name="a_city_id"]`).html('');

                        let options =
                            '<option value="" selected disabled>{{ __('Select City') }}</option>';

                        response.data.forEach(function(city) {
                            options += '<option value="' +
                                city.id +
                                '">' + city.name + '</option>';
                        });

                        $(`#${form} [name="a_city_id"]`).html(options).niceSelect(
                                'destroy')
                            .niceSelect();
                        $(`#${form} [name="a_city_id"]`).prop('disabled', false);

                        $(`#${form} [name="a_city_id"]`).niceSelect('update');

                        $('.preloader_area').addClass('d-none');
                    },
                    error: function(error) {
                        handleError(error);
                    },
                    complete: function() {
                        $('.preloader_area').addClass('d-none');
                        $(`#${form} [name="a_city_id"]`).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
