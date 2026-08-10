@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $razorpay_status = $paymentService->isActive($paymentService::RAZORPAY);
    $razorpay_key = $paymentService->getGatewayDetails($paymentService::RAZORPAY)->razorpay_key ?? '';
    $razorpay_name = $paymentService->getGatewayDetails($paymentService::RAZORPAY)->razorpay_name ?? '';
    $razorpay_description = $paymentService->getGatewayDetails($paymentService::RAZORPAY)->razorpay_description ?? '';
    $razorpay_image = $paymentService->getGatewayDetails($paymentService::RAZORPAY)->razorpay_image ?? '';
    $razorpay_theme_color = $paymentService->getGatewayDetails($paymentService::RAZORPAY)->razorpay_theme_color ?? '';
@endphp

{{-- Razorpay Payment --}}
@if ($razorpay_status)
    <div class="gap-2 mx-auto d-grid">
        <a class="btn btn-lg btn-primary" id="razorpayBtn" href="javascript:;">
            {{ __('Pay Now') }}
        </a>
    </div>

    <form class="d-none" action="{{ route('pay.via-razorpay', ['uuid' => $orderId, 'type' => $type]) }}" method="POST">
        @csrf

        <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ $razorpay_key }}"
            data-currency="{{ $currency_code }}" data-amount="{{ $payable_with_charge * 100 }}"
            data-buttontext="{{ __('Pay Now') }}" data-name="{{ $razorpay_name }}"
            data-description="{{ $razorpay_description }}" data-image="{{ asset($razorpay_image) }}"
            data-prefill.name="{{ auth()->user()->name ?? 'Test' }}" data-prefill.email="{{ auth()->user()->email }}"
            data-theme.color="{{ $razorpay_theme_color }}"></script>
    </form>

    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {

            var buttons = document.getElementsByClassName('razorpay-payment-button');
            if (buttons.length > 0) {
                buttons[0].click();
            } else {
                console.error("No element with class 'razorpay-payment-button' found.");
            }
        });
    </script>

    <script>
        "use strict";
        $(function() {
            $("#razorpayBtn").on("click", function() {
                $(".razorpay-payment-button").trigger('click');
            })
        });
    </script>
@endif
