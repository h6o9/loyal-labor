@extends('website.layouts.app')

@section('title')
    {{ __('Order Completed') }} - #{{ $order->order_id }} {{ __('Invoice') }} - {{ $setting->app_name }}
@endsection

@section('content')
    @use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')

    @php
        $relatedOrders = $order?->paymentDetails?->orders?->pluck('order_id') ?? collect([]);
        $amountPaid =
            $order?->paymentDetails?->paid_amount . ' ' . str($order?->payable_currency)->upper()->toString() ?? 'null';
    @endphp
    <section class="payment_success mt_100 mb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                    <div class="text-center">
                        <div class="img">
                            <img src="{{ asset('website/images/success.webp') }}" alt="{{ __('Success') }}">
                        </div>
                        <div class="show_message">
                            <h4>
                                @if ($relatedOrders->count() > 1)
                                    {{ __('Thanks for the order, your invoice ids are') }} @foreach ($relatedOrders as $relatedOrder)
                                        <a
                                            href="{{ route('website.guest.invoice', [
                                                'orderId' => $relatedOrder,
                                            ]) }}">#{{ $relatedOrder }}</a>
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('Thanks for the order, your invoice id is') }} <a
                                        href="{{ route('website.guest.invoice', [
                                            'orderId' => $order->order_id,
                                        ]) }}">#{{ $order->order_id }}</a>
                                @endif
                            </h4>
                            <p>
                                @if ($relatedOrders->count() > 1)
                                    {{ __('Track your orders') }} @foreach ($relatedOrders as $relatedOrder)
                                        <a
                                            href="{{ route('website.track.order', [
                                                'email' => $order->billingAddress->email ?? auth('web')->user()->email,
                                                'orderId' => $relatedOrder,
                                            ]) }}">#{{ $relatedOrder }}</a>
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('Track your order') }} <a
                                        href="{{ route('website.track.order', [
                                            'email' => $order->billingAddress->email ?? auth('web')->user()->email,
                                            'orderId' => $order->order_id,
                                        ]) }}">{{ __('here') }}</a>
                                @endif
                            </p>
                            @if ($order?->paymentDetails?->payment_status?->value == PaymentStatusEnum::COMPLETED->value)
                                <p>
                                    @if ($relatedOrders->count() > 1)
                                        <span>{{ __('You have paid total amount of :amount for all the orders', ['amount' => $amountPaid]) }}
                                        </span>
                                    @else
                                        <span>
                                            {{ __('You have paid total :amount', ['amount' => $amountPaid]) }}
                                        </span>
                                    @endif
                                </p>
                            @else
                                <span>
                                    {{ __('You have not paid yet!') }} <a
                                        href="{{ route('website.complete.payment', [
                                            'uuid' => $order->uuid,
                                            'type' => 'order',
                                        ]) }}">{{ __('Pay now') }}</a>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
