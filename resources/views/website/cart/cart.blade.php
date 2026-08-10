@extends('website.layouts.app')

@section('content')
    {{-- <!--============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('components::breadcrumb', ['title' => __('Cart view'), 'image' => 'cart'])
    {{-- <!--============================
        BREADCRUMBS END
    ==============================--> --}}

    {{-- <!--=============================
        CART VIEW START
    ==============================--> --}}
    <section class="wsus__cart_view pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="wsus__cart_area">
                <div class="row">
                    @if (!empty($changedItems))
                        <div class="col-12">
                            <div class="alert alert-warning">
                                {{ __('Some items prices have changed since you added them to the cart.') }}
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="wsus__cart_table">
                            <div class="table-responsive">
                                @forelse ($cart_contents as $vendor => $carts)
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="img" colspan="2">{{ $carts['0']['vendor_name'] }}'s
                                                    {{ __('Products') }}
                                                </th>
                                                <th class="price">{{ __('price') }}</th>
                                                <th class="discount">{{ __('Quantity') }}</th>
                                                <th class="total">{{ __('Total') }}</th>
                                                <th class="action">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <form id="cart_form" action="javascript::;" method="POST">
                                                @csrf
                                                @foreach ($carts as $cart)
                                                    <tr data-rowid="{{ $cart['rowid'] }}" @class([
                                                        'disabled' => in_array($cart['rowid'], $changedItems),
                                                    ])
                                                        @if (in_array($cart['rowid'], $changedItems)) title="{{ __('Price has changed, please review') }}" @endif>
                                                        <input name="rowid[]" type="hidden" value="{{ $cart['rowid'] }}">
                                                        <input name="qty[]" type="hidden" value="{{ $cart['qty'] }}">
                                                        <td class="img">
                                                            <a href="{{ route('website.product', $cart['slug']) }}">
                                                                <img class="img-fluid w-100"
                                                                    src="{{ asset($cart['image']) }}" alt="cart item">
                                                            </a>
                                                        </td>
                                                        <td class="description">
                                                            <h3><a href="{{ route('website.product', $cart['slug']) }}">
                                                                    {{ $cart['name'] }}
                                                                </a>
                                                            </h3>
                                                            @if ($cart['has_variant'])
                                                                <p>{{ $cart['variant']['attribute'] }}</p>
                                                            @endif
                                                            @if ($cart['vendor_id'])
                                                                <p>{{ __('Item by') }} {{ $cart['vendor_name'] }} <span
                                                                        class="badge {{ $cart['cod'] ? 'bg-success' : 'bg-danger text-decoration-line-through opacity-75' }}"
                                                                        title="{{ $cart['cod'] ? __('Cash On Delivery available') : __('Cash On Delivery not available') }}">{{ __('COD') }}</span>
                                                                </p>
                                                            @else
                                                                <span
                                                                    class="badge {{ $cart['cod'] ? 'bg-success' : 'bg-danger text-decoration-line-through opacity-75' }}"
                                                                    title="{{ $cart['cod'] ? __('Cash On Delivery available') : __('Cash On Delivery not available') }}">{{ __('COD') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="price">
                                                            <p>{{ currency($cart['price']) }}</p>
                                                        </td>
                                                        <td class="discount">
                                                            <div class="quantity">
                                                                <span class="minus">
                                                                    {{ __('-') }}
                                                                </span>
                                                                <span class="number">{{ $cart['qty'] }}</span>
                                                                <span class="plus">
                                                                    {{ __('+') }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="total">
                                                            <p>{{ currency($cart['sub_total']) }}</p>
                                                        </td>
                                                        <td class="action" data-rowid="{{ $cart['rowid'] }}">
                                                            <a class="remove_item" href="javascript:;"><i
                                                                    class="far fa-times"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </form>
                                        </tbody>
                                    </table>
                                @empty
                                    <div class="alert alert-info">
                                        {{ __('Your cart is empty') }}
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @if (count($cart_contents ?? []) > 0)
                    <div class="row justify-content-between mt_80">
                        <div class="col-xxl-5 col-xl-6 col-lg-6">
                            <form class="wsus__cart_coupone" id="coupon_form">
                                <input name="code" type="text" value="{{ session('coupon_code') }}"
                                    placeholder="{{ __('Discount Code') }}">
                                <button class="common_btn"
                                    type="submit">{{ session()->has('coupon_code') ? __('Applied') : __('Apply') }}</button>
                            </form>
                        </div>
                        <div class="col-xxl-4 col-xl-5 col-lg-5">
                            <div class="wsus__cart_price_list">
                                @include('components::cart-total')
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @include('components::preloader')
@endsection

@push('styles')
    <style>
        tr.disabled {
            opacity: 0.5;
        }

        tr.disabled * {
            pointer-events: none;
        }

        tr.disabled .remove_item {
            pointer-events: auto;
            opacity: 1 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        'use strict';
        $(document).ready(function() {

            // increase-btn
            $(document).on('click', '.show_update_price_alert', function(e) {
                e.preventDefault();

                toastr.warning("{{ __('Please update your cart before checkout') }}");
                return;
            });

            // increase-btn
            $(document).on('click', '.minus', function() {
                var row = $(this).parents('tr');
                var number = $(this).siblings('.number');
                var num = parseInt(number.text()) - 1;
                if (num < 1) {
                    num = 1;
                }
                number.text(num);
                updateCart(num, row.data('rowid'));
                // update  subtotal
                var price = row.find('.price').text();
                price = parseFloat(removeCurrency(price));

                var subtotal = num * price;
                row.find('.total').children('p').text(`{{ currency_icon() }}${subtotal.toFixed(2)}`);
            })

            $(document).on('click', '.plus', function() {
                var row = $(this).parents('tr');
                var number = $(this).siblings('.number');

                var num = parseInt(number.text()) + 1;
                number.text(num);

                // update  subtotal
                var price = row.find('.price').text();
                price = parseFloat(removeCurrency(price));
                updateCart(num, row.data('rowid'));
                var subtotal = num * price;
                row.find('.total').children('p').text(`{{ currency_icon() }}${subtotal.toFixed(2)}`);
            })

            // remove row
            $(document).on('click', '.remove_item', function() {
                $('.preloader_area').removeClass('d-none');

                const parentTr = $(this).closest('tr');
                const table = parentTr.closest('table');
                const rowid = parentTr.data('rowid');

                let url = "{{ route('website.cart.remove', ':id') }}".replace(":id", rowid);

                $.ajax({
                    url,
                    type: 'get',
                    success: function(response) {
                        parentTr.remove();

                        const hasRows = table.find('tbody tr').length > 0;

                        if (!hasRows) {
                            table.remove();
                        }

                        if (response.gtm) {
                            pushToDataLayer(response.gtm);
                        }

                        $("#cart_count").text(response.cartCount);
                        $('.wsus__cart_price_list').html(response.html);
                        resetCoupon();
                        $('.preloader_area').addClass('d-none');
                    },
                    error: function(err) {
                        handleError(error);
                        $('.preloader_area').addClass('d-none');
                    }
                });
            });

            // apply coupon

            $("#coupon_form").on("submit", function(e) {
                e.preventDefault();

                const coupon = $('[name="coupon"]').val();
                const subTotal = $('[name="subTotal"]')
                const tax = $('[name="tax"]')
                if (coupon === '') {
                    toastr.error('{{ __('Please enter a coupon code') }}');
                    return;
                }

                // tax_amount
                $('[name="tax_amount"]').val(tax.val());
                $('[name="amount"]').val(subTotal.val());
                const formData = $('#coupon_form').serialize();
                $.ajax({
                    type: 'get',
                    data: formData,
                    url: "{{ route('website.apply.coupon') }}",
                    beforeSend: function() {
                        $("#coupon_form button[type='submit']").html(
                            '<i class="fas fa-spinner fa-spin"></i> {{ __('Applying') }}'
                        )
                        $("#coupon_form button").attr("disabled", true);
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message)
                            $(".coupon_section").removeClass("d-none");
                            $('.coupon_name').text(response.coupon_code)
                            $('.wsus__cart_price_list').html(response.html)
                            $("#coupon_form button[type='submit']").html(
                                "{{ __('Applied') }}")
                            return;
                        }
                        toastr.error(response.message)

                        $("#coupon_form button[type='submit']").html(
                            "{{ __('Apply') }}"
                        )
                        $("#coupon_form button").attr("disabled", false);
                    },
                    error: function(response) {
                        if (response.status == 422) {
                            if (response.responseJSON.errors.coupon) toastr.error(
                                response.responseJSON.errors.coupon[0])
                        }

                        if (response.status == 500) {
                            toastr.error("{{ __('Server error occurred') }}")
                            $("#coupon_form button[type='submit']").html(
                                "{{ __('Apply') }}")
                            $("#coupon_form button").attr("disabled", false);
                        }

                        if (response.status == 403) {
                            toastr.error(response.responseJSON.message)
                            $("#coupon_form button[type='submit']").html(
                                "{{ __('Apply') }}")
                            $("#coupon_form button").attr("disabled", false);
                        }

                        $("#coupon_form button[type='submit']").html(
                            "{{ __('Apply') }}")
                        $("#coupon_form button").attr("disabled", false);
                    }
                });
            })

        })

        function updateCart(num, rowid) {
            $('.preloader_area').removeClass('d-none')
            let url = "{{ route('website.cart.update') }}"

            $.ajax({
                url,
                type: 'post',
                data: {
                    qty: num,
                    rowid: rowid
                },
                success: function(response) {
                    $("#cart_count").text(response.cartCount);
                    resetCoupon();
                    $('.wsus__cart_price_list').html(response.html);

                    if (response.gtm) {
                        pushToDataLayer(response.gtm);
                    }

                    $('.preloader_area').addClass('d-none');
                },
                error: function(error) {
                    handleError(error);
                    $('.preloader_area').addClass('d-none');
                }
            });
        }

        function resetCoupon() {
            $("#coupon_form").trigger("reset");
            $(".coupon_section").addClass("d-none");
            $('#coupon_form button[type="submit"]').html(
                "{{ __('Apply') }}");
        }
    </script>
@endpush
