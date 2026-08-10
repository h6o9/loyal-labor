<div class="alert alert-primary" role="alert">
    <h5>{{ __('Withdraw Limit') }} : {{ defaultCurrency($method->min_amount) }} -
        {{ defaultCurrency($method->max_amount) }}</h5>
    <h5>{{ __('Withdraw charge') }} : {{ $method->withdraw_charge }}%</h5>
    {!! clean($method->description) !!}
</div>
