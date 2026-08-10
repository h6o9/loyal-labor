@extends('website.layouts.app')

@section('title')
    {{ __('Pay Now') }} #{{ $purchase->order_id }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="complete_payment mt_100 mb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-10">
                    <div class="complete_payment_box">
                        @includeIf($paymentViewPath, [
                            'type' => $type,
                            'orderId' => $orderId,
                            'payable_with_charge' => $amount,
                            'currency_code' => $currencyCode,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
