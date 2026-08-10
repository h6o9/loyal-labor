@extends('seller.layouts.master')

@section('title')
    <title>{{ $title }}</title>
@endsection
@section('seller-content')
    @use('Modules\Order\app\Http\Enums\OrderStatus', 'OrderStatusEnum')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Order List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Order List') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="card-body" id="order-filter" action="{{ route($route) }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-{{ $route == 'admin.orders' ? 4 : 6 }} form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        @if ($route == 'seller.orders.index')
                                            <div class="col-md-2 form-group mb-3 mb-md-0">
                                                <x-admin.form-select class="form-select" id="status" name="status">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Status') }}" />
                                                    @foreach (OrderStatusEnum::cases() as $status)
                                                        <x-admin.select-option value="{{ $status->value }}"
                                                            :selected="request('status') == $status->value" text="{{ $status->getLabel() }}" />
                                                    @endforeach
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
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
                                <div class="table-responsive">
                                    <table class="table table-striped" id="ordersTable" style="width:100%">
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
            var ordersTable = initServerDataTable('#ordersTable', {
                url: "{{ route($route) }}",
                data: function(d) {
                    d.keyword = $('#order-filter [name="keyword"]').val();
                    d.status = $('#order-filter #status').val();
                    d.order_by = $('#order-filter #order_by').val();
                }
            }, [
                { data: 'user_name', name: 'user_name', orderable: false, searchable: false },
                { data: 'order_ref', name: 'order_id', orderable: false, searchable: false },
                { data: 'price', name: 'price', orderable: false, searchable: false },
                { data: 'status_label', name: 'status_label', orderable: false, searchable: false },
                { data: 'payment_label', name: 'payment_label', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#order-filter').on('submit', function(e) {
                e.preventDefault();
                ordersTable.ajax.reload();
            });

            $('#order-filter').on('change', function(e) {
                e.preventDefault();
                ordersTable.ajax.reload();
            });
        });
    </script>
@endpush
