@extends('admin.master_layout')
@section('title')
    <title>{{ __('All Customers') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('All Customer') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('All Customer') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="customer_filter_form" action="{{ route('admin.all-customers') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-2 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input id="keyword" name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="verified" name="verified">
                                                <x-admin.select-option value="" text="{{ __('Select Verified') }}" />
                                                <x-admin.select-option value="1" :selected="request('verified') == '1'"
                                                    text="{{ __('Verified') }}" />
                                                <x-admin.select-option value="0" :selected="request('verified') == '0'"
                                                    text="{{ __('Non-verified') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="seller" name="seller">
                                                <x-admin.select-option value="" text="{{ __('All Users') }}" />
                                                <x-admin.select-option value="1" :selected="request('seller') == '1'"
                                                    text="{{ __('Seller') }}" />
                                                <x-admin.select-option value="0" :selected="request('seller') == '0'"
                                                    text="{{ __('Customer') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="banned" name="banned">
                                                <x-admin.select-option value="" text="{{ __('Select Banned') }}" />
                                                <x-admin.select-option value="1" :selected="request('banned') == '1'"
                                                    text="{{ __('Banned') }}" />
                                                <x-admin.select-option value="0" :selected="request('banned') == '0'"
                                                    text="{{ __('Non-banned') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="par-page" name="par-page">
                                                <x-admin.select-option value="" text="{{ __('Per Page') }}" />
                                                <x-admin.select-option value="5" :selected="request('par-page') == '5'"
                                                    text="{{ __('5') }}" />
                                                <x-admin.select-option value="10" :selected="request('par-page') == '10'"
                                                    text="{{ __('10') }}" />
                                                <x-admin.select-option value="25" :selected="request('par-page') == '25'"
                                                    text="{{ __('25') }}" />
                                                <x-admin.select-option value="50" :selected="request('par-page') == '50'"
                                                    text="{{ __('50') }}" />
                                                <x-admin.select-option value="100" :selected="request('par-page') == '100'"
                                                    text="{{ __('100') }}" />
                                            </x-admin.form-select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="allCustomersTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Joined at') }}</th>
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
    @adminCan('customer.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
@adminCan('customer.delete')
    @push('js')
        <script>
            function deleteData(id) {
                $("#deleteForm").attr("action", '{{ url('/admin/customer-delete/') }}' + "/" + id)
            }
        </script>
    @endpush
@endadminCan

@push('js')
    <script>
        "use strict";

        $(document).ready(function() {
            var allCustomersTable = initServerDataTable('#allCustomersTable', {
                url: "{{ route('admin.all-customers') }}",
                data: function(d) {
                    d.keyword = $('#keyword').val();
                    d.verified = $('#verified').val();
                    d.seller = $('#seller').val();
                    d.banned = $('#banned').val();
                    d.order_by = $('#order_by').val();
                }
            }, [
                { data: 'name', name: 'name' },
                { data: 'email_col', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'status_badge', name: 'is_banned', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#customer_filter_form').on('submit', function(e) {
                e.preventDefault();
                allCustomersTable.ajax.reload();
            });

            $('#customer_filter_form').on('change', function(e) {
                if (e.target && e.target.id === 'par-page') {
                    allCustomersTable.page.len(parseInt($(e.target).val(), 10) || 10).draw();
                } else {
                    allCustomersTable.ajax.reload();
                }
            });
        });
    </script>
@endpush
