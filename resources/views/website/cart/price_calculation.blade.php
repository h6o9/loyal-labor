<div class="wsus__total_price">
    @isset($shipping_cost)
        <p>{{ __('Total Shipping') }} <span>{{ currency($shipping_cost) }}</span></p>
    @endisset
    <p>{{ __('Discount') }} <span>{{ '-' . currency($discount) }}</span></p>
    <p>{{ __('Tax') }} <span>{{ currency($tax) }}</span></p>
    @isset($gateway_charge)
        <p>{{ __('Gateway Charge') }} <span>{{ currency($gateway_charge) }}</span></p>
    @endisset
</div>
<h5>{{ __('Total') }}<span> {{ isset($payable) ? $payable . ' ' . $payable_currency : currency($total) }}</span>
</h5>
