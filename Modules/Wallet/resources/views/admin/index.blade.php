@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="row">
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Total Earning') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ currency($totalCreditAmount) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Total Send') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ currency($totalDebitAmount) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Total Pending') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ currency($totalPendingCreditAmount) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Auto Status Approve') }}</h4>
                            </div>
                            <div class="card-body">
                                <input data-toggle="toggle" data-onlabel="{{ __('Yes') }}"
                                    data-offlabel="{{ __('No') }}" data-onstyle="success" data-offstyle="danger"
                                    type="checkbox" onchange="autoApproveUpdate('wallet_amount_auto_approve')"
                                    @checked(getSettingStatus('wallet_amount_auto_approve', 'int'))>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pb-1">
                            <form id="filter_form" action="" method="GET">
                                <div class="row">
                                    <div class="col-lg-3 col-xl-4 col-md-6">
                                        <div class="form-group search-wrapper">
                                            <input class="form-control" name="keyword" type="text"
                                                value="{{ request()->get('keyword') }}"
                                                placeholder="{{ __('Search') }}..." autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xl-2 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" id="order_by" name="order_by">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="asc"
                                                    {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                    {{ __('Ascending') }}
                                                </option>
                                                <option value="desc"
                                                    {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                    {{ __('Descending') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    @if (isRoute('admin.wallet-history'))
                                        <div class="col-lg-3 col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">{{ __('Status') }}</option>
                                                    <option value="completed" @selected(request('status') == 'completed')>
                                                        {{ __('Completed') }}
                                                    </option>
                                                    <option value="pending" @selected(request('status') == 'pending')>
                                                        {{ __('Pending') }}
                                                    </option>
                                                    <option value="rejected" @selected(request('status') == 'rejected')>
                                                        {{ __('Rejected') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-3 col-xl-2 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control select2" id="vendor_id" name="vendor_id">
                                                <option value="" selected disabled>{{ __('Select Seller') }}
                                                </option>
                                                @foreach ($sellers as $vendor)
                                                    <option value="{{ $vendor->id }}" @selected(request('vendor_id') == $vendor->id)>
                                                        {{ $vendor->shop_name }} ({{ $vendor->user->name ?? '' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="walletHistoryTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('User') }}</th>
                                                <th>{{ __('Order') }}</th>
                                                <th>{{ __('For') }}</th>
                                                <th>{{ __('Gateway') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Deposit At') }}</th>
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
            </div>
        </section>
    </div>

    <div class="modal fade" id="delete" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Delete refund request') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">{{ __('Are You Sure to Delete this refund ?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Delete') }}" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script>
            $(function() {
                'use strict'

                // Delegated binding is required because rows are now injected
                // dynamically by the DataTable on every ajax draw.
                $(document).on('click', '.delete', function(e) {
                    e.preventDefault();
                    const modal = $('#delete');
                    modal.find('form').attr('action', $(this).data('url'));
                    modal.modal('show');
                });

                var walletHistoryTable = initServerDataTable('#walletHistoryTable', {
                    url: "{{ url()->current() }}",
                    data: function(d) {
                        d.keyword = $('#filter_form [name="keyword"]').val();
                        d.order_by = $('#order_by').val();
                        d.status = $('#status').val();
                        d.vendor_id = $('#vendor_id').val();
                    }
                }, [
                    { data: 'user_name', name: 'user.name', orderable: false },
                    { data: 'order_info', name: 'order.order_id', orderable: false, searchable: false },
                    { data: 'for_info', name: 'for_info', orderable: false, searchable: false },
                    { data: 'payment_gateway', name: 'payment_gateway' },
                    { data: 'amount', name: 'amount' },
                    { data: 'transaction_type_badge', name: 'transaction_type', orderable: false, searchable: false },
                    { data: 'payment_status_badge', name: 'payment_status', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ], {
                    searching: false,
                });

                // The existing filter form (keyword/order/status/vendor) now
                // reloads the DataTable via ajax instead of navigating the page.
                $('#filter_form').on('submit', function(e) {
                    e.preventDefault();
                    walletHistoryTable.ajax.reload();
                });
                $('#filter_form').on('change', function(e) {
                    walletHistoryTable.ajax.reload();
                });
            });

            function autoApproveUpdate(id) {
                handleStatus("{{ route('admin.wallet-auto-approve-status', ':id') }}".replace(':id', id));
            }
        </script>
    @endpush
@endsection
