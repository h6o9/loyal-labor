@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Order Details') }}</title>
@endsection

@section('seller-content')

    @use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')
    @use('Modules\Order\app\Http\Enums\OrderStatus', 'OrderStatusEnum')

    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Order Details') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Orders') => route('seller.orders.index'),
                __('Order Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header  d-flex justify-content-between align-items-center">
                                <h4>{{ __('Order Items') }}</h4>

                                <a class="btn btn-primary"
                                    href="{{ route('seller.orders.invoice', $order->order_id) }}">{{ __('Invoice') }}</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Quantity') }}</th>
                                                <th>{{ __('Current Stock') }}</th>
                                                <th>{{ __('Shop Commission') }}</th>
                                                <th>{{ __('Tax') }}</th>
                                                <th>{{ __('Total Price') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($order->items as $OrderItem)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.product.show', [
                                                            'product' => $OrderItem->product_id,
                                                        ]) }}"
                                                            target="_blank"
                                                            rel="noopener noreferrer">{{ $OrderItem->product_name }}</a>
                                                        {{ $OrderItem->is_variant == 1 ? '(SKU: ' . $OrderItem->product_sku . ' / ' . $OrderItem->options . ')' : '(SKU: ' . $OrderItem->product_sku . ')' }}
                                                    </td>
                                                    <td>
                                                        {{ currency($OrderItem->price) }}
                                                        {{ $OrderItem->is_flash_deal ? '(' . __('Flash Deal') . ')' : '' }}
                                                    </td>
                                                    <td>{{ $OrderItem->qty }}</td>
                                                    <td>
                                                        {{ $OrderItem->product->stock_qty ?? __('N/A') }}
                                                    </td>
                                                    <td>
                                                        {{ currency($OrderItem->commission) }}
                                                        (-{{ $OrderItem->commission_rate }}%)
                                                    </td>
                                                    <td>
                                                        {{ currency($OrderItem->tax_amount) }}
                                                    </td>
                                                    <td>
                                                        {{ currency($OrderItem->total_price) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="5">
                                                        {{ __('No Order Items Found') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-end" id="subtotal-table" colspan="1">
                                                    {{ __('Sub Total') }}
                                                </th>
                                                <th scope="row">
                                                    {{ currency($order->items->sum('price') ?? 0) }}
                                                </th>
                                                <th scope="row">{{ $order->items->sum('qty') ?? 0 }}</th>
                                                <th scope="row"></th>
                                                <th scope="row">{{ currency($order->items->sum('commission') ?? 0) }}
                                                </th>
                                                <th scope="row">
                                                    {{ currency($order->items->sum('tax_amount') ?? 0) }}</th>
                                                <th scope="row">
                                                    {{ currency($order->items->sum('total_price') ?? 0) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Order Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="20%">{{ __('Created At') }}</th>
                                                <td>{{ formattedDateTime($order->created_at) }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Order ID') }}</th>
                                                <td>#{{ $order->order_id }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Customer Name') }}</th>
                                                <td>{{ $order->is_guest_order ? $order?->shippingAddress?->name : $order->user->name ?? '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Customer Email') }}</th>
                                                <td>{{ $order->user->email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Customer Phone') }}</th>
                                                <td>{{ $order->user->phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Seller Name') }}</th>
                                                <td>{{ $order->seller->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Shop Name') }}</th>
                                                <td>{{ $order->seller->shop_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Seller Email') }}</th>
                                                <td>{{ $order->seller->email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Seller Phone') }}</th>
                                                <td>{{ $order->seller->phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Current Order Status') }}</th>
                                                <td>
                                                    <p>
                                                        <span class="me-3">
                                                            {{ $order->order_status->getLabel() ?? '' }}
                                                        </span>
                                                        @if ($order->order_status !== OrderStatusEnum::CANCELLED)
                                                            <button
                                                                class="btn btn-warning btn-sm" id="change-order-status"
                                                                data-id="{{ $order->id }}">
                                                                {{ __('Change Status') }}
                                                            </button>
                                                        @endif
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Payment Method') }}</th>
                                                <td>{{ getPaymentMethodLabel($order->payment_method) ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Current Payment Status') }}</th>
                                                <td>
                                                    <p>
                                                        <span class="me-3">
                                                            {{ $order->paymentDetails->payment_status->getLabel() ?? '' }}
                                                        </span>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Transaction ID') }}</th>
                                                <td>{{ $order->transaction_id ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Payable Currency') }}</th>
                                                <td>{{ $order->payable_currency ?? '' }}</td>
                                            </tr>
                                            @if ($order->coupon_code)
                                                <tr>
                                                    <th>{{ __('Coupon Code') }}</th>
                                                    <td>{{ $order->coupon_code ?? '' }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th>{{ __('Note') }}</th>
                                                <td>{{ $order->note ?? '' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Shipping Address') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">{{ __('Name') }}</th>
                                                <td>{{ $order->shippingAddress->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Email') }}</th>
                                                <td>{{ $order->shippingAddress->email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Phone') }}</th>
                                                <td>{{ $order->shippingAddress->phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Country') }}</th>
                                                <td>{{ $order->shippingAddress->country ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('State') }}</th>
                                                <td>{{ $order->shippingAddress->state ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('City') }}</th>
                                                <td>{{ $order->shippingAddress->city ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Zip') }}</th>
                                                <td>{{ $order->shippingAddress->zip_code ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Address') }}</th>
                                                <td>{{ $order->shippingAddress->address ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Walk In') }}</th>
                                                <td>
                                                    {{ $order->shippingAddress->walk_in_customer ? __('Yes') : __('No') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Type') }}</th>
                                                <td>{{ str($order->shippingAddress->type ?? 'null')->title() }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Payment Summary') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">{{ __('Quantity') }}</th>
                                                <td>{{ $order->items->sum('qty') }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Subtotal Amount') }}</th>
                                                <td>
                                                    {{ currency($order->items->sum('total_price')) }}
                                                </td>
                                            </tr>
                                            @if ($order->coupon_code)
                                                <tr>
                                                    <th>{{ __('Discount Amount') }}</th>
                                                    <td>
                                                        {{ currency($order->discount) }}
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th>{{ __('Tax Amount') }}</th>
                                                <td>
                                                    {{ currency($order->tax) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Shipping Cost') }}</th>
                                                <td>
                                                    {{ currency($order->shipping) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Gateway Fee') }}</th>
                                                <td>
                                                    {{ currency($order->gateway_fee) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Total Amount') }}</th>
                                                <td>
                                                    {{ currency($order->total_amount) }}
                                                </td>
                                            </tr>
                                            @if ($order->paymentDetails->payment_status == Modules\Order\app\Http\Enums\PaymentStatus::COMPLETED)
                                                @if ($order->transactionHistories)
                                                    <tr>
                                                        <th>{{ __('Paid At') }}</th>
                                                        <td>
                                                            {{ formattedDateTime($order->transactionHistories->last()->created_at ?? now()) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Billing Address') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">{{ __('Name') }}</th>
                                                <td>{{ $order->billingAddress->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Email') }}</th>
                                                <td>{{ $order->billingAddress->email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Phone') }}</th>
                                                <td>{{ $order->billingAddress->phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Country') }}</th>
                                                <td>{{ $order->billingAddress->country ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('State') }}</th>
                                                <td>{{ $order->billingAddress->state ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('City') }}</th>
                                                <td>{{ $order->billingAddress->city ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Zip') }}</th>
                                                <td>{{ $order->billingAddress->zip_code ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%">{{ __('Address') }}</th>
                                                <td>{{ $order->billingAddress->address ?? '' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Order Status Histroy') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('From') }}</th>
                                                <th>{{ __('To') }}</th>
                                                <th>{{ __('By') }}</th>
                                                <th>{{ __('Updated At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->orderStatusHistory as $orderStatusHistory)
                                                <tr>
                                                    <td>{{ $orderStatusHistory->from_status_enum->getLabel() }}</td>
                                                    <td>{{ $orderStatusHistory->to_status_enum->getLabel() }}</td>
                                                    <td>
                                                        @if ($orderStatusHistory->change_by == 'admin')
                                                            {{ $orderStatusHistory->changedByAdmin->name ?? '' }}
                                                        @elseif($orderStatusHistory->change_by == 'user')
                                                            {{ $orderStatusHistory->changedByUser->name ?? '' }}
                                                        @else
                                                            {{ $orderStatusHistory->change_by }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ formattedDateTime($orderStatusHistory->updated_at ? $orderStatusHistory->updated_at : $orderStatusHistory->created_at) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="4">
                                                        {{ __('No Order Status History Found') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Payment Status Histroy') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('From') }}</th>
                                                <th>{{ __('To') }}</th>
                                                <th>{{ __('By') }}</th>
                                                <th>{{ __('Updated At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->paymentStatusHistory as $orderStatusHistory)
                                                <tr>
                                                    <td>{{ $orderStatusHistory->from_status_enum->getLabel() }}</td>
                                                    <td>{{ $orderStatusHistory->to_status_enum->getLabel() }}</td>
                                                    <td>
                                                        @if ($orderStatusHistory->change_by == 'admin')
                                                            {{ $orderStatusHistory->changedByAdmin->name ?? '' }}
                                                        @elseif($orderStatusHistory->change_by == 'user')
                                                            {{ $orderStatusHistory->changedByUser->name ?? '' }}
                                                        @else
                                                            {{ $orderStatusHistory->change_by }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ formattedDateTime($orderStatusHistory->updated_at ? $orderStatusHistory->updated_at : $orderStatusHistory->created_at) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="4">
                                                        {{ __('No Payment Status History Found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    @include('seller.order.modals.status-update')
@endsection

@push('css')
    <style>
        .h-50 {
            height: 50px;
        }
    </style>
@endpush

@push('js')
    <script>
        "use strict";

        $(document).on('click', '#change-order-status', function() {
            $('#update-status').modal('show');
            let orderId = $(this).data('id');
        });
    </script>
@endpush
