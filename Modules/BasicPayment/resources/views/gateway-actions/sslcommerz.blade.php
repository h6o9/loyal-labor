@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $status = $paymentService->isActive($paymentService::SSLCOMMERZ);
    $paymentUrl = route('pay.via-sslcommerz', ['uuid' => $orderId, 'type' => $type]);
    $testMode = $paymentService->getGatewayDetails($paymentService::SSLCOMMERZ)->sslcommerz_test_mode ?? 0;
    $scriptUrl = $testMode
        ? 'https://sandbox.sslcommerz.com/embed.min.js?'
        : 'https://seamless-epay.sslcommerz.com/embed.min.js?';
@endphp

<form class="needs-validation" id="SslCommerz-form" method="POST" novalidate>
    <button class="btn btn-primary btn-lg btn-block" id="sslczPayBtn" token="" postdata=""
        order="If you already have the transaction generated for current order" endpoint="{{ $paymentUrl }}">
        {{ __('Pay Now') }}
    </button>
</form>

<script>
    "use strict";

    var obj = {};

    $('#sslczPayBtn').prop('postdata', obj);

    (function(window, document) {
        var loader = function() {
            var script = document.createElement("script"),
                tag = document.getElementsByTagName("script")[0];
            script.src = "{{ $scriptUrl }}" + Math.random().toString(36).substring(
                7);

            script.onload = function() {
                setTimeout(function() {
                    var payBtn = document.getElementById("sslczPayBtn");
                    if (payBtn) {
                        payBtn.click();
                    }
                }, 1000);
            };
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload",
            loader);
    })(window, document);
</script>
