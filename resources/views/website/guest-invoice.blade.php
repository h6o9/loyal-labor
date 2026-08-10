@extends('website.layouts.app')

@section('title')
    #{{ $order->order_id }} {{ __('Invoice') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="wsus__guest_order_invoice mt_100 mb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__invoice_top">
                            <div class="wsus__invoice_logo">
                                <img class="img-fluid w-100" src="{{ asset($setting->logo) }}" alt="logo">
                            </div>
                            <div class="wsus__invoice_heading">
                                <h2>{{ __('Invoice') }}</h2>
                            </div>
                        </div>

                        <div class="wsus__invoice_description">
                            <h4>{{ __('Invoice to') }}:</h4>
                            <div class="row justify-content-between">
                                <div class="col-xl-6 col-sm-6">
                                    <div class="wsus__invoice_address">
                                        <h5>{{ $order->shippingAddress->name }}, {{ $order->shippingAddress->phone }}</h5>
                                        <p>{{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }},
                                            {{ $order->shippingAddress->state }}
                                            {{ $order->shippingAddress->zip_code }}</p>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-sm-5">
                                    <div class="wsus__invoice_date">
                                        <h5>{{ __('Invoice') }}#<span><a
                                                    href="{{ route('website.track.order', [
                                                        'email' => $order->billingAddress->email ?? auth('web')->user()->email,
                                                        'orderId' => $order->order_id,
                                                    ]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">{{ $order->order_id }}</a></span>
                                        </h5>
                                        <h5 class="date">
                                            {{ __('Date') }}<span>{{ formattedDate($order->created_at) }}</span>
                                        </h5>
                                        <h5 class="date">
                                            {{ __('Payment') }}<span>{{ $order->paymentDetails->payment_status->getLabel() }}</span>
                                        </h5>
                                        <h5 class="date">
                                            {{ __('Status') }}<span>{{ $order->order_status->getLabel() }}</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wsus__invoice_table">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th class="serial">
                                                        {{ __('SL') }}
                                                    </th>
                                                    <th class="description">
                                                        {{ __('Item Description') }}
                                                    </th>
                                                    <th class="price">
                                                        {{ __('Price') }}
                                                    </th>
                                                    <th class="price">
                                                        {{ __('Tax') }}
                                                    </th>
                                                    <th class="quantity">
                                                        {{ __('Quantity') }}
                                                    </th>
                                                    <th class="total">
                                                        {{ __('Total') }}
                                                    </th>
                                                </tr>
                                                @foreach ($order->items as $product)
                                                    <tr>
                                                        <td class="serial">
                                                            <p>{{ $loop->index + 1 }}
                                                            </p>
                                                        </td>
                                                        <td class="description">
                                                            <p>{{ $product->product_name }} @if ($product->is_variant)
                                                                    ({{ $product->options }})
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td class="price">
                                                            <p>{{ currency($product->price) }}</p>
                                                        </td>
                                                        <td class="price">
                                                            <p>{{ currency($product->tax_amount) }}</p>
                                                        </td>
                                                        <td class="quantity">
                                                            <p>{{ $product->qty }}</p>
                                                        </td>
                                                        <td class="total">
                                                            <p>{{ currency($product->total_price) }}</p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wsus__invoice_final_total">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="wsus__invoice_final_total_left">
                                        <p>{{ $order->note }}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="wsus__invoice_final_total_right">
                                        <h6>{{ __('Subtotal') }}:<span>{{ currency($order->sub_total) }}</span></h6>
                                        <h6>{{ __('Tax') }}:<span>{{ currency($order->tax) }}</span></h6>
                                        <h6>{{ __('Shipping') }}:<span>{{ currency($order->shipping) }}</span></h6>
                                        <h6>{{ __('Gateway Charge') }}:<span>{{ currency($order->gateway_fee) }}</span>
                                        </h6>
                                        <h5>{{ __('Total') }}: <span
                                                class="{{ $order?->paymentDetails?->payment_status->value == 'completed' ? 'text-success' : 'text-danger' }}">{{ $order->payable_amount }}
                                                {{ $order->payable_currency }}</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
