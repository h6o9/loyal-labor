@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $status = $paymentService->isActive($paymentService::CRYPTO);
    $paymentUrl = route('pay.via-crypto', ['uuid' => $orderId, 'type' => $type]);
@endphp

@if ($status)
    <!-- Pay with Crypto -->
    <a class="btn btn-primary" id="cryptoPayBtn" href="{{ $paymentUrl }}">
        {{ __('Pay with CoinGate') }}
    </a>

    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cryptoPayBtn').click();
        });
    </script>
@else
    <div class="alert alert-danger">
        {{ __('Crypto payment gateway is not active.') }}
    </div>
@endif
