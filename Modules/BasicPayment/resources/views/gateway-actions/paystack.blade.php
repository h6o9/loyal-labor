@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $status = $paymentService->isActive($paymentService::PAYSTACK);
    $paystack_public_key = $paymentService->getGatewayDetails($paymentService::PAYSTACK)->paystack_public_key ?? '';
@endphp

{{-- Paystack Payment --}}
@if ($status)
    <div class="gap-2 mx-auto d-grid">
        <button class="btn btn-lg btn-primary" id="paystackPaymentBtn" type="button"
            onclick="payWithPaystack()">{{ __('Pay Now') }}</button>
    </div>

    <script src="https://js.paystack.co/v1/inline.js"></script>

    <script>
        "use strict";

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('paystackPaymentBtn').click();
        });

        function payWithPaystack() {
            if (APP_MODE_DEMO) {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }

            var handler = PaystackPop.setup({
                key: '{{ $paystack_public_key }}',
                email: '{{ auth()->user()->email }}',
                amount: '{{ $payable_with_charge * 100 }}',
                currency: "{{ $currency_code }}",
                callback: function(response) {
                    let reference = response.reference;
                    let tnx_id = response.transaction;
                    let _token = "{{ csrf_token() }}";

                    $.ajax({
                        type: "post",
                        data: {
                            reference,
                            tnx_id,
                            _token,
                        },
                        url: "{{ route('pay.via-paystack', ['uuid' => $orderId, 'type' => $type]) }}",
                        success: function(response) {
                            window.location.href =
                                "{{ route('website.invoice', ['uuid' => $orderId]) }}";
                        },
                        error: function(response) {
                            toastr.error("{{ __('Payment failed, please try again') }}");
                            window.location.reload();
                        }
                    });
                },
                onClose: function() {
                    toastr.error('window closed');
                }
            });
            handler.openIframe();
        }
    </script>
@endif
