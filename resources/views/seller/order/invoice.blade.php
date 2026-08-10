@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Order Details') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Order Details') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Order Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card" id="order_invoice_print">
                            <div class="card-body">
                                <div class="container mb-5 mt-3">
                                    <div class="row d-flex align-items-baseline justify-content-between">
                                        <div class="col-xl-12">
                                            <p class="invoice_title">{{ __('Invoice') }} >>
                                                <strong>{{ __('ID') }}:
                                                    #{{ $order->order_id ?? '' }}</strong>
                                            </p>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="container">
                                        <div id="order_invoice_print">
                                            <div class="row">
                                                <div class="col-xl-8">
                                                    <ul class="list-unstyled">
                                                        <li class="text-muted">
                                                            {{ __('To') }}: <a
                                                                href="">{{ optional($order->billingAddress)->name }}</a>
                                                        </li>
                                                        <li class="text-muted">
                                                            {{ optional($order->billingAddress)->full_address }}
                                                        </li>
                                                        <li class="text-muted"><i class="fas fa-envelope"></i>
                                                            {{ optional($order->billingAddress)->email ?? '' }}
                                                        </li>
                                                        <li class="text-muted"><i class="fas fa-phone"></i>
                                                            {{ optional($order->billingAddress)->phone ?? '' }}
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-xl-4">
                                                    <ul class="list-unstyled">
                                                        <li class="text-muted"><i class="fas fa-circle fa-circle-color"></i>
                                                            <span class="fw-bold">{{ __('TrxID') }}:</span>
                                                            {!! nl2br($order->transaction_id) !!}
                                                        </li>
                                                        <li class="text-muted pt-2"><i
                                                                class="fas fa-circle fa-circle-color"></i> <span
                                                                class="fw-bold">{{ __('Order Date') }}:
                                                            </span>{{ formattedDate($order->created_at ?? '') }}</li>
                                                        <li class="text-muted mt-2"><i
                                                                class="fas fa-circle fa-circle-color"></i> <span
                                                                class="me-1 fw-bold">{{ __('Status') }}:</span>
                                                            @php
                                                                $class =
                                                                    $order->order_status->value == 'success' ||
                                                                    $order->order_status->value == 'delivered'
                                                                        ? 'bg-success text-white'
                                                                        : ($order->order_status->value == 'pending'
                                                                            ? 'bg-warning text-black'
                                                                            : 'bg-danger text-white');
                                                            @endphp
                                                            <span class="badge {{ $class }} fw-bold">
                                                                {{ ucfirst($order->order_status->value) }}</span>
                                                        <li class="text-muted mt-2"><i
                                                                class="fas fa-circle fa-circle-color"></i> <span
                                                                class="me-1 fw-bold">{{ __('Payment Status') }}:</span>
                                                            @php
                                                                $class =
                                                                    $order->paymentDetails->payment_status->value ==
                                                                    'success'
                                                                        ? 'bg-success text-white'
                                                                        : ($order->paymentDetails->payment_status
                                                                            ->value == 'pending'
                                                                            ? 'bg-warning text-black'
                                                                            : 'bg-danger text-black');
                                                            @endphp
                                                            <span class="badge {{ $class }} fw-bold">
                                                                {{ ucfirst($order->paymentDetails->payment_status->value) }}
                                                                ({{ str($order->payment_method)->replace('_', ' ')->title() }})</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="row my-2 mx-1 justify-content-center">
                                                <table class="table table-striped table-borderless">
                                                    <thead class="text-white bg-circle-color">
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">{{ __('Item Description') }}</th>
                                                            <th scope="col">{{ __('Price') }}</th>
                                                            <th scope="col">{{ __('Quantity') }}</th>
                                                            <th scope="col">{{ __('Tax') }}</th>
                                                            <th scope="col">{{ __('Total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->items as $orderItem)
                                                            <tr>
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <td>
                                                                    {{ optional($orderItem)->product_name ?? '' }}
                                                                    @if ($orderItem->optionas)
                                                                        <span
                                                                            class="text-info">({{ $orderItem->optionas }})
                                                                    @endif
                                                                </td>
                                                                <td>{{ currency(optional($orderItem)->price ?? 0) }}
                                                                </td>
                                                                <td>{{ $orderItem->qty }}</td>
                                                                <td>{{ currency($orderItem->tax_amount) }}</td>
                                                                <td>{{ currency($orderItem->total_price) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <th class="text-end" id="subtotal-table" colspan="2">
                                                                {{ __('Sub Total') }}
                                                            </th>
                                                            <th scope="row">
                                                                {{ currency($order->items->sum('price') ?? 0) }}
                                                            </th>
                                                            <th scope="row">{{ $order->items->sum('qty') ?? 0 }}</th>
                                                            <th scope="row">
                                                                {{ currency($order->items->sum('tax_amount') ?? 0) }}</th>
                                                            <th scope="row">
                                                                {{ currency($order->items->sum('total_price') ?? 0) }}</th>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-8">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            {{ __('Shipping Address') }}
                                                        </li>
                                                        <li class="text-muted">
                                                            {{ optional($order->shippingAddress)->name }}
                                                        </li>
                                                        <li class="text-muted">
                                                            {{ optional($order->shippingAddress)->full_address }}
                                                        </li>
                                                        <li class="text-muted"><i class="fas fa-envelope"></i>
                                                            {{ optional($order->shippingAddress)->email ?? '' }}
                                                        </li>
                                                        <li class="text-muted"><i class="fas fa-phone"></i>
                                                            {{ optional($order->shippingAddress)->phone ?? '' }}
                                                        </li>
                                                    </ul>

                                                    <p class="ms-3">{{ htmlDecode(optional($order)->note ?? '') }}
                                                    </p>
                                                </div>
                                                <div class="col-xl-3">
                                                    <ul class="list-unstyled">
                                                        @if ($order->coupon_code)
                                                            <li class="text-muted ms-3"><span
                                                                    class="text-black me-4">{{ __('Discount') }}</span>-{{ currency($order->discount) }}
                                                            </li>
                                                        @endif
                                                        <li class="text-muted ms-3 mt-2"><span
                                                                class="text-black me-4">{{ __('Total Tax') }}</span>{{ currency($order->tax) }}
                                                        </li>
                                                        <li class="text-muted ms-3 mt-2"><span
                                                                class="text-black me-4">{{ __('Shipping Charge') }}</span>{{ currency($order->shipping) }}
                                                        </li>
                                                        <li class="text-muted ms-3 mt-2"><span
                                                                class="text-black me-4">{{ __('Total Price') }}</span>{{ currency($order->sub_total) }}
                                                        </li>
                                                        <li class="text-muted ms-3 mt-2"><span
                                                                class="text-black me-4">{{ __('Gateway Charge') }}</span>{{ currency($order->gateway_fee) }}
                                                        </li>
                                                        <li class="text-muted ms-3 mt-2"><span
                                                                class="text-black me-4">{{ __('Payable') }}</span>{{ currency($order->payable_amount_without_rate) }}<small>({{ __('In Admin Currency Rate') }})</small>
                                                        </li>
                                                    </ul>
                                                    <p class="text-black float-start"><span class="text-black me-3">
                                                            {{ __('Grand Payable') }}</span><span
                                                            class="fs-25">{{ $order->payable_amount }}
                                                            {{ $order->payable_currency }}</span>
                                                    </p>
                                                    @if ($order->paymentDetails->payment_status->value == 'success')
                                                        <p class="text-black float-start"><span class="text-black me-3">
                                                                {{ __('Total Paid') }}</span><span
                                                                class="fs-25">{{ $order->paid_amount }}
                                                                {{ $order->payable_currency }}</span></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-primary" onclick="printInvoice()">{{ __('Print Invoice') }}</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .invoice_title {
            color: #7e8d9f;
            font-size: 20px;
        }

        .fa-circle-color {
            color: #84B0CA !important;
        }

        .bg-circle-color {
            background-color: #84B0CA !important;
        }

        .fs-25 {
            font-size: 25px;
        }
    </style>
@endsection

@push('js')
    <script>
        "use strict";

        function printInvoice() {
            let body = document.body.innerHTML;

            let data = document.getElementById('order_invoice_print').innerHTML;

            document.body.innerHTML = data;

            window.print();

            document.body.innerHTML = body;
        }
    </script>
@endpush
