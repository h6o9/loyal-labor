@extends('admin.master_layout')
@section('title')
    <title>{{ __('Coupon List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Coupon List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Coupon List') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Coupon List')" />
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.coupon.create') }}"><i
                                            class="fas fa-plus"></i> {{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="couponTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Coupon Code') }}</th>
                                                <th>{{ __('Min Spend') }}</th>
                                                <th>{{ __('Discount') }}</th>
                                                <th>{{ __('Start time') }}</th>
                                                <th>{{ __('End time') }}</th>
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
            </div>
        </section>
    </div>

    <x-admin.delete-modal />
@endsection

@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#couponTable', "{{ route('admin.coupon.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'coupon_code', name: 'coupon_code' },
                { data: 'min_spend', name: 'minimum_spend' },
                { data: 'discount_value', name: 'discount' },
                { data: 'start_time', name: 'start_date' },
                { data: 'end_time', name: 'expired_date' },
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ], {
                drawCallback: function() {
                    // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
                }
            });
        });

        "use strict"

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/coupon/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.message) {
                        toastr.error(err.responseJSON.message);
                    } else {
                        toastr.error(__('Something went wrong, please try again'));
                    }
                }
            });
        }
    </script>
@endpush
