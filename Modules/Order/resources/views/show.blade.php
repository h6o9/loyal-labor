@extends('admin.master_layout')

@section('title')
    <title>{{ __('Order Details') }}</title>
@endsection

@section('admin-content')
    @use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')
    @use('Modules\Order\app\Http\Enums\OrderStatus', 'OrderStatusEnum')

    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Order Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Order List') => route('admin.orders'),
                __('Order Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header  d-flex justify-content-between align-items-center">
                                <h4>{{ __('Order Items') }}</h4>

                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.orders.invoice', $order->order_id) }}"><i
                                            class="fa fa-file-invoice"></i> {{ __('Invoice') }}</a>

                                    <a class="btn btn-danger btn-sm delete" href="javascript:;" role="button" rel="nofollow"
                                        onclick="deleteData({{ $order->id }})">
                                        <i class="fa fa-trash"></i> {{ __('Delete') }}
                                    </a>
                                </div>
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
                                                <th>{{ __('Seller Commission') }}</th>
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
                                                <th></th>
                                                <th>{{ currency($order->items->sum('commission') ?? 0) }}</th>
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
                                                <td>
                                                    {{ $order->is_guest_order ? $order?->shippingAddress?->name : $order->user->name ?? '' }}
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
                                                            <a href="#status-history">
                                                                {{ $order->order_status->getLabel() ?? '' }}
                                                            </a>
                                                        </span>
                                                        @if ($order->order_status !== OrderStatusEnum::CANCELLED)
                                                            <button
                                                                class="btn btn-warning btn-sm" id="change-order-status"
                                                                data-id="{{ $order->id }}">
                                                                <i class="fas fa-sync"></i> {{ __('Change') }}
                                                            </button>
                                                        @endif
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Payment Method') }}</th>
                                                <td>{{ $order->payment_method ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Current Payment Status') }}</th>
                                                <td>
                                                    <p>
                                                        <span class="me-3">
                                                            <a href="#payment-amounts">
                                                                {{ $order->paymentDetails->payment_status->getLabel() ?? '' }}
                                                            </a>
                                                        </span>
                                                        @if ($order->paymentDetails->payment_method !== 'bank')
                                                            <button
                                                                class="btn btn-danger btn-sm" id="change-payment-status"
                                                                data-id="{{ $order->id }}">
                                                                <i class="fas fa-sync"></i> {{ __('Change') }}
                                                            </button>
                                                        @endif
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
                            <div class="card-footer d-flex justify-content-end">
                                @if (
                                    $order->payment_method == 'bank' &&
                                        ($order->paymentDetails->payment_status == PaymentStatusEnum::PROCESSING ||
                                            $order->paymentDetails->payment_status == PaymentStatusEnum::REJECTED) &&
                                        $order->order_status !== OrderStatusEnum::CANCELLED)
                                    <button class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#approveBankPayment">{{ __('Approve Bank Payment') }}</button>
                                @endif
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
                                                <td>
                                                    {{ str($order->shippingAddress->type ?? 'null')->title() }}
                                                </td>
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
                                                <th>{{ __('Tax Amount') }}</th>
                                                <td>
                                                    {{ currency($order->tax) }}
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
                                                <th>{{ __('Subtotal Amount') }}</th>
                                                <td>
                                                    {{ currency($order->items->sum('total_price')) }}
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
                                            <tr>
                                                <th>{{ __('Total Payable') }}</th>
                                                <td>
                                                    {{ $order->payable_amount }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Payable Currency') }}</th>
                                                <td>
                                                    {{ $order->payable_currency }}
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
                                                <tr>
                                                    <th>{{ __('Paid Amount') }}</th>
                                                    <td>
                                                        {{ $order->paid_amount }} {{ $order->payable_currency }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (
                                                $order->payment_method === 'bank' &&
                                                    isset($order->paymentDetails) &&
                                                    !empty($order?->paymentDetails?->payment_details))
                                                @php
                                                    $details = json_decode(
                                                        $order->paymentDetails->payment_details,
                                                        true,
                                                    );
                                                @endphp

                                                @forelse ($details as $key => $value)
                                                    <tr>
                                                        <th>{{ Str::title($key) }}</th>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2">{{ __('No bank payment details found.') }}</td>
                                                    </tr>
                                                @endforelse
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ __('Order Status Histroy') }}</h4>
                                <div>
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.orders.all-status-updates', ['id' => $order->order_id, 'type' => 'order']) }}">{{ __('Update History') }}</a>
                                </div>
                            </div>
                            <div class="card-body" id="status-history">
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
                                                    <td>{{ $orderStatusHistory->from_status_enum->getLabel() }}
                                                    </td>
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ __('Transaction Histroy') }}</h4>
                                <div>
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.orders.all-transactions', ['id' => $order->order_id]) }}">{{ __('Transaction History') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('TrxID') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Updated') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->transactionHistories as $trHistory)
                                                <tr>
                                                    <td>{{ $trHistory->transaction_id }}</td>
                                                    <td>{{ $trHistory->payment_method }}</td>
                                                    <td>
                                                        {{ currency($trHistory->amount) }}
                                                        {{ $trHistory->currency }}
                                                    </td>
                                                    <td>{{ $trHistory->status }}</td>
                                                    <td>
                                                        {{ formattedDateTime($trHistory->updated_at ? $trHistory->updated_at : $trHistory->created_at) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="5">
                                                        {{ __('No Transaction Found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $relatedOrders =
                            $order->paymentDetails->orders
                                ->where('order_id', '!=', $order->order_id)
                                ->pluck('order_id') ?? collect([]);
                    @endphp
                    @if ($relatedOrders->isNotEmpty())
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('Shared Payments') }}</h4>
                                </div>
                                <div class="card-body" id="payment-amounts">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('Shares Payment with') }}</th>
                                                    <td>
                                                        @foreach ($relatedOrders as $relatedOrder)
                                                            <a
                                                                href="{{ route('admin.order', $relatedOrder) }}">
                                                                #{{ $relatedOrder }}
                                                            </a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ __('Payment Status Histroy') }}</h4>
                                <div>
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.orders.all-status-updates', ['id' => $order->order_id, 'type' => 'payment']) }}">{{ __('Update History') }}</a>
                                </div>
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
                                                    <td>{{ $orderStatusHistory->from_status_enum->getLabel() }}
                                                    </td>
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

    @include('order::modals.status-update')

    @include('order::modals.payment-status')

    @if ($order->payment_method == 'bank')
        @include('order::modals.bank-payment')
    @endif
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

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.order-delete', ':id') }}';
            url = url.replace(':id', id);
            var text =
                "{{ __('Are you sure you want to delete this order? Deleting it may cause calculation errors. We recommend changing the status to Cancelled instead.') }}";
            $("#deleteForm").attr('action', url);
            $("#deleteModalText").text(text);
            $('#deleteModal').modal('show');
        }

        $(document).on('click', '#change-order-status', function() {
            $('#update-status').modal('show');
            let orderId = $(this).data('id');
        });

        $(document).on('click', '#change-payment-status', function() {
            $('#update-payment-status').modal('show');
        });

        $(document).on('change', '#payment_status', function() {
            let paymentStatus = $(this).val();

            @if ($order->payment_method == 'hand_cash')
                if (paymentStatus == '{{ PaymentStatusEnum::COMPLETED->value }}') {
                    $('#cash_paid_amount_div').removeClass('d-none').addClass('d-block');
                } else {
                    $('#cash_paid_amount_div').removeClass('d-block').addClass('d-none');
                }
            @else
                if (paymentStatus == '{{ PaymentStatusEnum::REJECTED->value }}') {
                    $('#payment-status-update-form-comment').val("Hello [[name]], ")
                }
            @endif

        });
    </script>
@endpush
