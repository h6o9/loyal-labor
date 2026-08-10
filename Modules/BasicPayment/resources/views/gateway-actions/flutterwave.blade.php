@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $status = $paymentService->isActive($paymentService::FLUTTERWAVE);
@endphp

@if ($status)
    <div class="gap-2 mx-auto d-grid">
        <button class="btn btn-lg btn-primary" id="payBtn" onclick="flutterwavePayment()">
            {{ __('Pay Now') }}
        </button>
    </div>

    <script src="https://checkout.flutterwave.com/v3.js"></script>

    <script>
        "use strict";

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('payBtn').click();
        });

        function flutterwavePayment() {
            if (APP_MODE_DEMO) {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }

            FlutterwaveCheckout({
                public_key: "{{ $paymentService->getGatewayDetails($paymentService::FLUTTERWAVE)->flutterwave_public_key }}",
                tx_ref: "{{ substr(rand(0, time()), 0, 10) }}",
                amount: "{{ $payable_with_charge }}",
                currency: "{{ $currency_code }}",
                country: "{{ $country_code ?? 'NG' }}",
                payment_options: " ",
                customer: {
                    email: "{{ $purchase->billingAddress->email ?? 'test@gmail.com' }}",
                    phone_number: "{{ $purchase->billingAddress->phone ?? '0000000000' }}",
                    name: "{{ $purchase->billingAddress->name ?? 'Test' }}",
                },
                callback: function(data) {
                    var tnx_id = data.transaction_id;
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        type: 'post',
                        data: {
                            tnx_id,
                            _token,
                        },
                        url: "{{ route('pay.via-flutterwave', ['uuid' => $orderId, 'type' => $type]) }}",
                        success: function(response) {
                            window.location.href =
                                "{{ route('website.invoice', ['uuid' => $orderId]) }}";
                        },
                        error: function(err) {
                            toastr.error("{{ __('Payment failed, please try again') }}");
                            window.location.reload();
                        }
                    });
                },
                customizations: {
                    title: "{{ $paymentService->getGatewayDetails($paymentService::FLUTTERWAVE)->flutterwave_app_name }}",
                    logo: "{{ asset($paymentService->getGatewayDetails($paymentService::FLUTTERWAVE)->flutterwave_image) }}",
                },
            });

        }
    </script>
@endif
