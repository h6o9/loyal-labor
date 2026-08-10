@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    @use('Modules\Order\app\Http\Enums\OrderStatus', 'OrderStatusEnum')
    @use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')

    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="order-filter" action="{{ route($route) }}" method="GET">
                                    <div class="row">
                                        <div
                                            class="col-md-{{ $route == 'admin.orders' ? 3 : 4 }} col-lg-3 col-xl-2 form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="select2" id="user" name="user">
                                                <x-admin.select-option value="" text="{{ __('Select user') }}" />
                                                @foreach ($users as $user)
                                                    <x-admin.select-option value="{{ $user->id }}" :selected="$user->id == request('user')"
                                                        text="{{ $user->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        @if ($route == 'admin.orders')
                                            <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                                <x-admin.form-select class="form-select" id="status" name="status">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Order Status') }}" />
                                                    @foreach (OrderStatusEnum::cases() as $status)
                                                        <x-admin.select-option value="{{ $status->value }}"
                                                            :selected="request('status') == $status->value" text="{{ $status->getLabel() }}" />
                                                    @endforeach
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="payment_status"
                                                name="payment_status">
                                                <x-admin.select-option value=""
                                                    text="{{ __('Select Payment Status') }}" />
                                                @foreach (PaymentStatusEnum::cases() as $status)
                                                    <x-admin.select-option value="{{ $status->value }}" :selected="request('payment_status') == $status->value"
                                                        text="{{ $status->getLabel() }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body text-center">
                                @if ($route == 'admin.orders')
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="ordersTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('User') }}</th>
                                                    <th>{{ __('Order ID') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Payment') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="ordersSubTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SN') }}</th>
                                                    <th>{{ __('User') }}</th>
                                                    <th>{{ __('Order ID') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Payment') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                @endif
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
        'use strict'

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.order-delete', ':id') }}';
            url = url.replace(':id', id);
            var text =
                "{{ __('Are you sure you want to delete this order? Deleting it may cause calculation errors. We recommend changing the status to Cancelled instead.') }}";
            $("#deleteForm").attr('action', url);
            $("#deleteModalText").text(text);
            $('#deleteModal').modal('show');
        }

        @if ($route == 'admin.orders')
            $(function() {
                var ordersTable = initServerDataTable('#ordersTable', {
                    url: "{{ route('admin.orders', array_filter(['vendor_id' => request('vendor_id')])) }}",
                    data: function(d) {
                        d.keyword = $('#order-filter [name="keyword"]').val();
                        d.user = $('#order-filter [name="user"]').val();
                        d.status = $('#order-filter [name="status"]').val();
                        d.payment_status = $('#order-filter [name="payment_status"]').val();
                        d.order_by = $('#order-filter [name="order_by"]').val();
                    }
                }, [
                    { data: 'customer_name', name: 'user.name', orderable: false },
                    { data: 'order_id', name: 'order_id' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'order_status_label', name: 'order_status' },
                    { data: 'payment_status_label', name: 'paymentDetails.payment_status', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ], {
                    searching: false,
                });

                $('#order-filter').on('submit', function(e) {
                    e.preventDefault();
                    ordersTable.ajax.reload();
                });

                $('#order-filter select').on('change', function() {
                    ordersTable.ajax.reload();
                });
            });
        @else
            $(function() {
                var ordersSubTable = initServerDataTable('#ordersSubTable', {
                    url: "{{ url()->current() }}",
                    data: function(d) {
                        d.keyword = $('#order-filter [name="keyword"]').val();
                        d.user = $('#order-filter [name="user"]').val();
                        d.order_by = $('#order-filter [name="order_by"]').val();
                    }
                }, [
                    { data: 'customer_name', name: 'user.name', orderable: false },
                    { data: 'order_id', name: 'order_id' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'order_status_label', name: 'order_status' },
                    { data: 'payment_status_label', name: 'paymentDetails.payment_status', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ], {
                    searching: false,
                });

                $('#order-filter').on('submit', function(e) {
                    e.preventDefault();
                    ordersSubTable.ajax.reload();
                });

                $('#order-filter select').on('change', function() {
                    ordersSubTable.ajax.reload();
                });
            });
        @endif
    </script>
@endpush
