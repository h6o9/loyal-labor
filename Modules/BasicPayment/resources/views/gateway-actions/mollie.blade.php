@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $status = $paymentService->isActive($paymentService::MOLLIE);
@endphp

@if ($status)
    <div class="gap-2 mx-auto d-grid">
        <a class="btn btn-lg btn-primary" id="payBtn"
            href="{{ route('pay.via-mollie', ['uuid' => $orderId, 'type' => $type]) }}">
            {{ __('Pay Now') }}
        </a>
    </div>

    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('payBtn').click();
        });
    </script>
@endif
