@extends('user.layout.app')

@section('title')
    {{ __('Orders') }} || {{ $setting->app_name }}
@endsection

@section('user-breadcrumb')
    @include('components::breadcrumb', ['title' => __('Orders'), 'image' => 'orders'])
@endsection

@section('user-content')
    <div class="wsus__dashboard_contant">
        <div class="wsus__dashboard_contant_top">
            <div class="wsus__dashboard_heading">
                <h5>{{ __('Orders') }}</h5>
                <p><span>{{ __('Total Orders') }}: {{ $total_order ?? 0 }}</span>, <span>{{ __('Pending Orders') }}:
                        {{ $pending_order ?? 0 }}, </span>, <span>{{ __('Delivered Orders') }}:
                        {{ $delivered_order ?? 0 }}</span>, <span>{{ __('Unpaid Orders') }}: {{ $totalUnpaid ?? 0 }}
                </p>
            </div>
        </div>

        <div class="wsus__dash_order_table">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="details">
                                        {{ __('Invoice') }}
                                    </th>
                                    <th class="invoice">
                                        {{ __('Date') }}
                                    </th>
                                    <th class="date">
                                        {{ __('Method') }}
                                    </th>
                                    <th class="method">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="icon">
                                        {{ __('Action') }}
                                    </th>
                                </tr>
                                @php
                                    use Modules\Order\app\Http\Enums\PaymentStatus as PaymentStatus;
                                    use Modules\Order\app\Http\Enums\OrderStatus as OrderStatus;
                                    use Carbon\Carbon as Carbon;
                                @endphp
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="details">
                                            <a
                                                href="{{ route('website.track.order', [
                                                    'email' => $order->billingAddress->email ?? auth('web')->user()->email,
                                                    'orderId' => $order->order_id,
                                                ]) }}">#{{ $order->order_id }}</a>
                                            <p>
                                                {{ $order->items_count ?? 0 }} {{ __('Products') }}
                                            </p>
                                        </td>
                                        <td class="invoice">
                                            <p>{{ formattedDateTime($order->created_at) }}</p>
                                        </td>

                                        <td class="date">
                                            <p @class([
                                                'text-success' =>
                                                    $order->paymentDetails->payment_status == PaymentStatus::COMPLETED,
                                                'text-danger' =>
                                                    $order->paymentDetails->payment_status == PaymentStatus::FAILED,
                                                'text-warning' =>
                                                    $order->paymentDetails->payment_status == PaymentStatus::PENDING,
                                            ])>
                                                {{ getPaymentMethodLabel($order->payment_method) }}</p>
                                        </td>
                                        <td class="method">
                                            <p>{{ $order->order_status->getLabel() }}</p>
                                        </td>
                                        <td class="icon">
                                            <a class="order_view"
                                                href="{{ route('website.user.invoice', ['uuid' => $order->uuid]) }}">
                                                <i class="far fa-eye"></i>
                                            </a>

                                            @if (
                                                $order->paymentDetails->payment_status !== PaymentStatus::PENDING &&
                                                    $order->order_status->value == OrderStatus::DELIVERED->value)
                                                <a class="order_review"
                                                    href="{{ route('website.user.reviews.add', ['order_id' => $order->order_id]) }}">
                                                    <i class="far fa-star"></i>
                                                </a>
                                            @endif

                                            @if (
                                                ($order->paymentDetails->payment_status !== PaymentStatus::COMPLETED ||
                                                    $order->paymentDetails->payment_status !== PaymentStatus::REJECTED) &&
                                                    $order->order_status == OrderStatus::PENDING &&
                                                    $order->paymentDetails->payment_method !== 'hand_cash')
                                                <a class="order_card"
                                                    href="{{ route('website.complete.payment', ['uuid' => $order->uuid]) }}">
                                                    <i class="fa fa-credit-card" title="{{ __('Complete Payment') }}"
                                                        aria-hidden="true"></i>
                                                </a>
                                            @endif

                                            @php
                                                $cancelAvailable = false;

                                                $orderCancelLimit = (int) getSettings('order_cancel_minutes_before');

                                                $orderCreatedAt = Carbon::parse($order->created_at);

                                                $orderCancelTime = $orderCreatedAt
                                                    ->copy()
                                                    ->addMinutes($orderCancelLimit);

                                                $now = Carbon::now();

                                                if ($now->lt($orderCancelTime)) {
                                                    $cancelAvailable = true;
                                                }

                                                $cancelAvailable =
                                                    $cancelAvailable &&
                                                    $order->paymentDetails->payment_status !==
                                                        PaymentStatus::COMPLETED &&
                                                    $order->order_status == OrderStatus::PENDING &&
                                                    $order->order_status !== OrderStatus::CANCELLED;
                                            @endphp

                                            @if ($cancelAvailable)
                                                <a class="cancel-order" data-uuid="{{ $order->uuid }}"
                                                    data-order-cancel-limit="{{ $orderCancelTime->toIso8601String() }}"
                                                    href="javascript:void(0)">
                                                    <i class="far fa-times" title="{{ __('Cancel Order') }}"
                                                        aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="5">{{ __('No Data Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12 wow fadeInUp">
                    {{ $orders->links('components::pagination') }}
                </div>

            </div>
        </div>
    </div>

    <div class="wsus__delete_modal">
        <div class="modal fade" id="cancelModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>{{ __('Are you sure?') }}</h4>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="wsus__delete_modal">
                            <div class="wsus__delete_modal_text">

                                <div class="time-left">
                                    <p class="text-danger font-weight-bold" id="cancel-timer"></p>
                                    <p>{{ __('Do you really want to cancel this order?') }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="wsus__delete_modal_btn">
                            <form id="cancelForm" action="" method="post">
                                @csrf
                                <button class="btn btn-danger" type="submit">{{ __('Yes, Cancel Order') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.cancel-order', function() {
            var uuid = $(this).data('uuid');
            var expireTimeStr = $(this).data('order-cancel-limit');
            var cancelUrl = '{{ route('website.user.orders.cancel', ':uuid') }}'.replace(':uuid', uuid);

            $('#cancelForm').attr('action', cancelUrl);

            var countDownDate = new Date(expireTimeStr).getTime();

            var countdownElement = document.getElementById('cancel-timer');

            if (window.cancelCountdownTimer) {
                clearInterval(window.cancelCountdownTimer);
            }

            function startCountdown() {
                var now = new Date().getTime();
                var distance = countDownDate - now;

                if (distance <= 0) {
                    countdownElement.innerText = "{{ __('Cancellation time expired.') }}";
                    $('#cancelForm button[type="submit"]').prop('disabled', true);
                    return;
                }

                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerText = `Cancel within: ${minutes}m ${seconds}s`;
                $('#cancelForm button[type="submit"]').prop('disabled', false);
            }

            startCountdown();

            window.cancelCountdownTimer = setInterval(function() {
                startCountdown();

                var now = new Date().getTime();
                var distance = countDownDate - now;
                if (distance <= 0) {
                    clearInterval(window.cancelCountdownTimer);
                }
            }, 1000);

            setTimeout(function() {
                $('#cancelModal').modal('show');
            }, 200);
        });
    </script>
@endpush
