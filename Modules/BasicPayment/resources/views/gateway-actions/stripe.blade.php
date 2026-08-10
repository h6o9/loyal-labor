@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $stripe_status = $paymentService->isActive($paymentService::STRIPE);
    $stripe_key = $paymentService->getGatewayDetails($paymentService::STRIPE)->stripe_key ?? '';
@endphp

@if ($paymentService->isSupportedGateway($paymentService::STRIPE) && $stripe_status)
    {{-- Stripe Payment --}}

    <form class="require-validation" id="payment-form" data-cc-on-file="false"
        data-stripe-publishable-key="{{ $stripe_key }}" action="{{ route('pay.via-stripe') }}" method="post">
        @csrf
        <input name="order_uuid" type="hidden" value="{{ $orderId }}">
        <input name="type" type="hidden" value="{{ $type ?? 'order' }}">
        <div class="row">
            <div class="mt-2 col-md-12">
                <div class="form-group required">
                    <label for="card_number">{{ __('Card Number') }}<span class="text-danger">*</span></label>
                    <input class='form-control card-number' id="card_number" name="card_number" type='text'
                        autocomplete='off' size='20' placeholder="{{ __('Card Number') }}" autocomplete="off">

                </div>
            </div>
            <div class="mt-3 col-md-12">
                <div class="form-group expiration required">
                    <label for="month">{{ __('Month') }}<span class="text-danger">*</span></label>
                    <input class='form-control card-expiry-month' id="month" name="month" type='text'
                        size='2' placeholder="{{ __('MM') }}" autocomplete="off">
                </div>
            </div>
            <div class="mt-3 col-md-12">
                <div class="form-group expiration required">
                    <label for="year">{{ __('Year') }}<span class="text-danger">*</span></label>
                    <input class='form-control card-expiry-year' id="year" name="year" type='text'
                        size='4' autocomplete="off" placeholder="{{ __('YY') }}">
                </div>
            </div>
            <div class="my-3 col-md-12">
                <div class="form-group cvc required">
                    <label for="cvc">{{ __('CVC') }}<span class="text-danger">*</span></label>
                    <input class='form-control card-cvc' id="cvc" name="cvc" type='text' autocomplete='off'
                        size='4' placeholder="{{ __('CVC') }}" autocomplete="off">
                </div>
            </div>
        </div>

        <div class='form-row row'>
            <div class='col-md-12 error form-group d-none '>
                <div class='alert-danger alert'>
                    {{ __('Please correct the errors and try again.') }}
                </div>
            </div>
        </div>

        <div class="gap-2 mx-auto d-grid">
            <button class="btn btn-primary btn-block" id="stripePaymentSubmitButton"
                type="submit">{{ __('Payment') }}</button>
        </div>
    </form>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">
        "use strict";
        $(function() {
            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {
                $('#stripePaymentSubmitButton').prop('disabled', true);

                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;
                $errorMessage.addClass('d-none');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('d-none');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('#stripePaymentSubmitButton').prop('disabled', false);
                    $('.error')
                        .removeClass('d-none')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    var token = response['id'];

                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    $form.get(0).submit();
                }
            }
        });
    </script>
@endif
