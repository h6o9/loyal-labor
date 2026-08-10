@extends('website.layouts.app')

@section('title')
    {{ __('Gift Cards') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <!--============================ BREADCRUMBS START ==============================-->
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Gift Cards') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Gift Cards') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================ BREADCRUMBS END ==============================-->

    <section class="wsus__coupon mt_95 xs_mt_75 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @forelse ($coupons as $coupon)
                    <div class="col-xxl-4 col-lg-6">
                        <div class="wsus__coupon_item">
                            <h3>{{ $coupon->name }}</h3>
                            @if ($coupon->free_shipping == 1)
                                <h6><span>{{ __('Free Shipping on orders over') }}</span></h6>
                            @elseif ($coupon->is_percent == 1)
                                <h6><span>{{ round($coupon->discount) }}% {{ __('OFF') }}</span>
                                    {{ __('your next purchase!') }}</h6>
                            @else
                                <h6><span>{{ currency($coupon->discount) }}</span>
                                    {{ __('Discount on your next purchase!') }}</h6>
                            @endif
                            <p>{{ __('Use coupon code') }}:</p>
                            <div class="wsus__coupon_code">
                                <span id="copy_coupon_code_{{ $coupon->id }}">{{ $coupon->coupon_code }}</span>
                                <button class="copy_coupon_code"
                                    data-coupon-id="{{ $coupon->id }}">{{ __('Copy') }}</button>
                            </div>
                            <p>{{ __('Valid until') }}
                                <b>{{ $coupon->is_never_expired == 1 ? __('Never') : (!blank($coupon->expired_date) ? formattedDate($coupon->expired_date) : __('Forever')) }}</b>
                            </p>
                            <p>{{ __('Terms and conditions apply.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center">
                            <p>{{ __('No gift cards available.') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.copy_coupon_code', function() {
            const couponId = $(this).data('coupon-id');
            const couponText = $('#copy_coupon_code_' + couponId).text().trim();

            const $tempInput = $('<input>');
            $('body').append($tempInput);
            $tempInput.val(couponText).select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    toastr.success('{{ __('Coupon code copied:') }} ' + couponText);
                } else {
                    toastr.error('{{ __('Failed to copy coupon code.') }}');
                }
            } catch (err) {
                toastr.error('{{ __('Oops! Copy failed.') }}');
            }

            $tempInput.remove();
        });
    </script>
@endpush
