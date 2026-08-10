@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $paypal_status = $paymentService->isActive($paymentService::PAYPAL);
@endphp

@if ($paymentService->isSupportedGateway($paymentService::PAYPAL) && $paypal_status)
    <div class="gap-2 mx-auto d-grid">
        <a class="btn btn-block btn-success" id="payBtn"
            href="{{ route('pay.via-paypal', ['id' => $orderId, 'type' => $type]) }}">{{ __('Pay Now') }}
        </a>
    </div>

    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('payBtn').click();
        });
    </script>
@endif
