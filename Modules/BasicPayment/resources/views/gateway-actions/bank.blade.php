@php
    $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
    $bank_status = $paymentService->isActive($paymentService::BANK_PAYMENT);
    $bank_information = $paymentService->getGatewayDetails($paymentService::BANK_PAYMENT)->bank_information ?? '';
@endphp

{{-- Bank Payment --}}
@if ($bank_status)
    <p>{!! nl2br($bank_information) !!}</p>

    <form action="{{ route('pay.via-bank') }}" method="post">
        @csrf
        <input name="order_uuid" type="hidden" value="{{ $orderId }}">
        <input name="order_type" type="hidden" value="{{ $type ?? 'order' }}">
        <!-- Bank Name -->
        <div class="mt-3 form-group">
            <label for="bank_name">{{ __('Bank Name') }} <span class="text-danger">*</span></label>
            <input class="form-control" id="bank_name" name="bank_name" type="text"
                placeholder="{{ __('Your bank name') }}" required>
        </div>

        <!-- Account Number -->
        <div class="mt-3 form-group">
            <label for="account_number">{{ __('Account Number') }} <span class="text-danger">*</span></label>
            <input class="form-control" id="account_number" name="account_number" type="text"
                placeholder="{{ __('Your bank account number') }}" required>
        </div>

        <!-- Routing Number -->
        <div class="mt-3 form-group">
            <label for="routing_number">{{ __('Routing Number') }}</label>
            <input class="form-control" id="routing_number" name="routing_number" type="text"
                placeholder="{{ __('Your bank routing number') }}">
        </div>

        <!-- Branch -->
        <div class="mt-3 form-group">
            <label for="branch">{{ __('Branch') }} <span class="text-danger">*</span></label>
            <input class="form-control" id="branch" name="branch" type="text"
                placeholder="{{ __('Your bank branch name') }}" required>
        </div>

        <!-- Transaction -->
        <div class="mt-3 form-group">
            <label for="transaction">{{ __('Transaction') }} <span class="text-danger">*</span></label>
            <input class="form-control" id="transaction" name="transaction" type="text"
                placeholder="{{ __('Provide your transaction') }}" required>
        </div>

        <button class="mt-3 btn btn-primary">{{ __('Submit') }}</button>
    </form>
@endif
