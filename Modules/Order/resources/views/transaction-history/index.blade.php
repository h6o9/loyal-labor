@extends('admin.master_layout')

@section('title')
    <title>{{ __('Transaction History') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Transaction History') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Transaction History') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="filter" action="javascript:;">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="method" name="method">
                                                <x-admin.select-option value="" text="{{ __('All Methods') }}" />
                                                @foreach ($transactionMethods as $transactionMethod)
                                                    <x-admin.select-option value="{{ $transactionMethod }}"
                                                        :selected="request('method') == $transactionMethod"
                                                        text="{{ str($transactionMethod)->replace('_', ' ')->title() }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Transaction History')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table min-height-600" id="transactionHistoryTable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Order ID') }}</th>
                                                <th>{{ __('Transaction ID') }}</th>
                                                <th>{{ __('Payment Method') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Paid At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        "use strict";

        $(document).ready(function() {
            var transactionHistoryTable = initServerDataTable('#transactionHistoryTable', {
                url: "{{ route('admin.orders.all-transactions', $id ? ['id' => $id] : []) }}",
                data: function(d) {
                    d.keyword = $('#filter [name="keyword"]').val();
                    d.order_by = $('#order_by').val();
                    d.method = $('#method').val();
                }
            }, [
                { data: 'order_display', name: 'order.order_id', orderable: false, searchable: false },
                { data: 'transaction_id', name: 'transaction_id' },
                { data: 'payment_method_display', name: 'payment_method' },
                { data: 'amount_display', name: 'amount' },
                { data: 'customer_name', name: 'user.name', orderable: false },
                { data: 'paid_at', name: 'created_at' },
            ], {
                searching: false,
            });

            $('#filter').on('submit', function(e) {
                e.preventDefault();
                transactionHistoryTable.ajax.reload();
            });

            $('#filter select').on('change', function() {
                transactionHistoryTable.ajax.reload();
            });
        });
    </script>
@endpush
