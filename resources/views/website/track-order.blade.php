@extends('website.layouts.app')

@section('title')
    {{ __('Track Order') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Track Order') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Track Order') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--============================TRACKING ORDER START=============================-->
    <section class="wsus__track_page pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-10 col-lg-8 m-auto">
                    <form class="tack_form" action="{{ route('website.track.order') }}" method="GET">
                        <h4 class="text-center">{{ __('Order Tracking') }}</h4>
                        <p class="text-center">{{ __('Tracking your order status') }}</p>
                        <div class="wsus__track_input">
                            <label class="d-block mb-2">{{ __('Invoice ID') }}<span class="text-danger">*</span></label>
                            <input name="orderId" type="text" value="{{ old('orderId', request('orderId')) }}"
                                placeholder="INV-00000002">
                        </div>
                        <div class="wsus__track_input">
                            <label class="d-block mb-2">{{ __('Shipping/Billing Email') }}<span
                                    class="text-danger">*</span></label>
                            <input name="email" type="email" value="{{ old('email', request('email')) }}"
                                placeholder="example@email.com">
                        </div>
                        <button class="common_btn" type="submit">{{ __('Track') }}</button>
                    </form>
                </div>
            </div>
            @use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')
            @if ($order)
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__track_header">
                            <div class="wsus__track_header_text">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single">
                                            <h5>{{ __('Created at') }}:</h5>
                                            <p>{{ formattedDateTime($order->created_at) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single">
                                            <h5>{{ __('Shop Name') }}:</h5>
                                            <p><a href="{{ route('website.shop', ['slug' => $order->seller->shop_slug]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">{{ $order->seller->shop_name }}</a></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single">
                                            <h5>{{ __('Status') }}:</h5>
                                            <p>
                                                @if (auth('web')->check() && auth('web')->id() == $order->user_id)
                                                    <a href="{{ route('website.user.invoice', ['uuid' => $order->uuid]) }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer">{{ $order->order_status->getLabel() }}</a>
                                                @else
                                                    {{ $order->order_status->getLabel() }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-lg-3">
                                        <div class="wsus__track_header_single">
                                            <h5>{{ __('Payment') }}:</h5>
                                            <p>{{ $order->paymentDetails->payment_status == PaymentStatusEnum::COMPLETED ? str($order->transaction_id)->mask('*', -9, 6) : $order->paymentDetails->payment_status->getLabel() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <ul class="pro_trckr">
                            @php
                                $orderCancel = false;
                                $className = '';

                                if ($order->order_status->serial() == 5) {
                                    $className = 'check_mark';
                                } elseif ($order->order_status->serial() == 6) {
                                    $orderCancel = true;
                                    $className = 'red_mark';
                                }
                            @endphp

                            <li
                                class="{{ $orderCancel ? 'red_mark' : ($order->order_status->serial() >= 1 ? 'check_mark' : '') }}">
                                {{ $order->order_status->serial() >= 1 ? __('Order approved') : __('Order pending') }}</li>
                            <li
                                class="{{ $orderCancel ? 'red_mark' : ($order->order_status->serial() >= 2 ? 'check_mark' : '') }}">
                                {{ __('Order Processing') }}
                            </li>
                            <li
                                class="{{ $orderCancel ? 'red_mark' : ($order->order_status->serial() >= 4 ? 'check_mark' : '') }}">
                                {{ __('On The Way') }}</li>
                            <li class="{{ $className }}">
                                {{ $orderCancel ? __('Order Cancelled') : __('Order Delivered') }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-12">
                        <div class="col-12">
                            <div class="track_pro_table">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="img">{{ __('Product') }}</th>
                                                <th class="description"></th>
                                                <th class="price">{{ __('Price') }}</th>
                                                <th class="discount">{{ __('Quantity') }}</th>
                                                <th class="tax">{{ __('Tax') }}</th>
                                                <th class="total">{{ __('Total') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->items as $orderItem)
                                                @php
                                                    $isProductAvailable = $orderItem?->product ? true : false;
                                                @endphp
                                                <tr>
                                                    <td class="img">
                                                        <a
                                                            href="{{ $isProductAvailable ? route('website.product', ['product' => $orderItem?->product?->slug ?? '']) : '#' }}">
                                                            <img class="img-fluid w-100"
                                                                src="{{ $isProductAvailable ? asset($orderItem->product->thumbnail_image) : $setting->default_avatar }}"
                                                                alt="{{ $orderItem->product_name }}">
                                                        </a>
                                                    </td>
                                                    <td class="description">
                                                        <h3>
                                                            <a
                                                                href="{{ $isProductAvailable ? route('website.product', ['product' => $orderItem?->product?->slug ?? '']) : '#' }}">
                                                                {{ $orderItem->product_name }}
                                                            </a>
                                                        </h3>
                                                        @if ($orderItem->options)
                                                            <p>{{ $orderItem->options }} ({{ $orderItem->product_sku }})
                                                            </p>
                                                        @else
                                                            <p>{{ $orderItem->product_sku }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="price">
                                                        <p>{{ currency($orderItem->price) }}</p>
                                                    </td>
                                                    <td class="discount">
                                                        <p>x{{ $orderItem->qty }}</p>
                                                    </td>
                                                    <td class="tax">
                                                        <p>{{ currency($orderItem->tax_amount) }}</p>
                                                    </td>
                                                    <td class="total">
                                                        <p>{{ currency($orderItem->total_price) }}</p>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <h3>{{ __('No Order Items Found') }}</h3>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        @if (count($order->items) > 0)
                                            <tfoot>
                                                <tr>
                                                    <td class="text-right" colspan="4">
                                                    </td>
                                                    <td class="text-center">
                                                        <h3>{{ __('Total') }}</h3>
                                                    </td>
                                                    <td class="text-center">
                                                        <h3>{{ $order->payable_amount }} {{ $order->payable_currency }}
                                                        </h3>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (auth('web')->check() && auth('web')->id() == $order->user_id)
                        <div class="col-xl-12">
                            <a class="common_btn"
                                href="{{ route('website.user.orders') }}">{{ __('Back to My Orders') }}</a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
    <!--============================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    TRACKING ORDER END
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ==============================-->
@endsection
