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
                'My withdraw' => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Wallet Balance') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ defaultCurrency($currentWalletAmount) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Credit') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ defaultCurrency($totalWalletCreditAmount) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-minus"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Debit') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ defaultCurrency($totalWalletDebitAmount) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Rejected Withdraw') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalRejectedRequest }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form class="form_padding" id="withdraw-filter" action="{{ url()->current() }}"
                                    method="GET">
                                    <div class="row">
                                        <div class="col-xl-6 col-md-4 form-group">
                                            <input class="form-control" name="keyword" type="text"
                                                value="{{ request()->get('keyword') }}" placeholder="{{ __('Search') }}">
                                        </div>
                                        @if (Route::is('admin.withdraw-list'))
                                            <div class="col-xl-2 col-md-4 form-group">
                                                <select class="form-select" id="status" name="status">
                                                    <option value="">{{ __('Select Status') }}</option>
                                                    <option value="pending"
                                                        {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                        {{ __('Pending') }}
                                                    </option>
                                                    <option value="approved"
                                                        {{ request('status') == 'approved' ? 'selected' : '' }}>
                                                        {{ __('Approved') }}
                                                    </option>
                                                    <option value="rejected"
                                                        {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                                        {{ __('Rejected') }}
                                                    </option>
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-xl-2 col-md-4 form-group">
                                            <select class="form-select" id="order_by" name="order_by">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="1" {{ request('order_by') == '1' ? 'selected' : '' }}>
                                                    {{ __('ASC') }}
                                                </option>
                                                <option value="0" {{ request('order_by') == '0' ? 'selected' : '' }}>
                                                    {{ __('DESC') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ __('My withdraw') }}</h4>

                                <a class="btn btn-primary" href="{{ route('seller.my-withdraw.create') }}"><i
                                        class="fas fa-plus"></i>
                                    {{ __('New withdraw') }}</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="withdrawsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Charge') }}</th>
                                                <th>{{ __('Total Amount') }}</th>
                                                <th>{{ __('Withdraw Amount') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
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
        $(document).ready(function() {
            var withdrawsTable = initServerDataTable('#withdrawsTable', {
                url: "{{ route('seller.my-withdraw.index') }}",
                data: function(d) {
                    d.keyword = $('#withdraw-filter [name="keyword"]').val();
                    d.status = $('#withdraw-filter #status').val();
                    d.order_by = $('#withdraw-filter #order_by').val();
                }
            }, [
                { data: 'method', name: 'method' },
                { data: 'charge_amount', name: 'charge_amount', orderable: false, searchable: false },
                { data: 'total_amount_col', name: 'total_amount', orderable: false, searchable: false },
                { data: 'withdraw_amount_col', name: 'withdraw_amount', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#withdraw-filter').on('submit', function(e) {
                e.preventDefault();
                withdrawsTable.ajax.reload();
            });

            $('#withdraw-filter').on('change', function(e) {
                e.preventDefault();
                withdrawsTable.ajax.reload();
            });
        });
    </script>
@endpush
