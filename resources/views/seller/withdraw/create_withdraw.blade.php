@extends('seller.layouts.master')

@section('title')
    <title>{{ __('My withdraw') }}</title>
@endsection

@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('My withdraw') }}" :list="[
                'Dashboard' => route('seller.dashboard'),
                'My withdraw' => route('seller.my-withdraw.index'),
                'Request Withdraw' => '#',
            ]" />

            <div class="section-body">
                <a class="btn btn-primary" href="{{ route('seller.my-withdraw.index') }}"><i class="fas fa-list"></i>
                    {{ __('My withdraw') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-primary">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>{{ __('Total Balance') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ defaultCurrency($currentWalletAmount) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-warning">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>{{ __('Pending Request') }}</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ $totalPendingRequest }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('seller.my-withdraw.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label" for="method_id">{{ __('Withdraw Method') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="method_id" name="method_id">
                                            <option value="">{{ __('Select Method') }}</option>
                                            @foreach ($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="withdraw_amount">{{ __('Withdraw Amount') }}<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" id="withdraw_amount" name="withdraw_amount"
                                            type="text">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="account_info">{{ __('Account Information') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control text-area-5" id="" id="account_info" name="account_info" cols="30"
                                            rows="10"></textarea>
                                    </div>

                                    <button class="btn btn-primary" type="submit">{{ __('Send Request') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 d-none" id="method_des_box">
                        <div class="card">
                            <div class="card-body" id="method_des">

                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#method_id").on('change', function() {
                    var methodId = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "{{ url('/seller/get-withdraw-account-info/') }}" + "/" +
                            methodId,
                        success: function(response) {
                            $("#method_des").html(response)
                            $("#method_des_box").removeClass('d-none')
                        },
                        error: function(err) {}
                    })

                    if (!methodId) {
                        $("#method_des_box").addClass('d-none')
                    }

                })
            });

        })(jQuery);
    </script>
@endpush
