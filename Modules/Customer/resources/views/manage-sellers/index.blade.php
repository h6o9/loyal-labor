@extends('admin.master_layout')
@section('title')
    <title>
        {{ $title }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('All Sellers') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('All Sellers') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="sellers_filter_form" action="{{ url()->current() }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input id="keyword" name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="verified" name="verified">
                                                <x-admin.select-option value="" text="{{ __('Select Verified') }}" />
                                                <x-admin.select-option value="1" :selected="request('verified') == '1'"
                                                    text="{{ __('Verified (KYC)') }}" />
                                                <x-admin.select-option value="0" :selected="request('verified') == '0'"
                                                    text="{{ __('Non-verified (KYC)') }}" />
                                                <x-admin.select-option value="2" :selected="request('verified') == '2'"
                                                    text="{{ __('User Email Verified') }}" />
                                                <x-admin.select-option value="3" :selected="request('verified') == '3'"
                                                    text="{{ __('Seller Email Non-Verified') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="banned" name="banned">
                                                <x-admin.select-option value="" text="{{ __('Select Banned') }}" />
                                                <x-admin.select-option value="1" :selected="request('banned') == '1'"
                                                    text="{{ __('Banned Account') }}" />
                                                <x-admin.select-option value="0" :selected="request('banned') == '0'"
                                                    text="{{ __('Non-banned Account') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
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
                                    <table class="table table-striped" id="sellersTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('KYC') }}</th>
                                                <th>{{ __('Statistic') }}</th>
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
@endsection

@push('js')
    <script>
        "use strict";

        @adminCan('sellers.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/sellers/delete-seller/') }}" + "/" + id)
        }
        @endadminCan

        var sellersTable = initServerDataTable('#sellersTable', {
            url: "{{ url()->current() }}",
            data: function(d) {
                d.keyword = $('#keyword').val();
                d.verified = $('#verified').val();
                d.banned = $('#banned').val();
                d.order_by = $('#order_by').val();
            }
        }, [
            { data: 'name_col', name: 'name' },
            { data: 'email_col', name: 'seller.email', orderable: false },
            { data: 'kyc_col', name: 'kyc', orderable: false, searchable: false },
            { data: 'statistic_col', name: 'statistic', orderable: false, searchable: false },
            { data: 'status_badge', name: 'seller.status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]);

        $('#sellers_filter_form').on('submit', function(e) {
            e.preventDefault();
            sellersTable.ajax.reload();
        });

        $('#sellers_filter_form').on('change', function(e) {
            if (e.target && e.target.id === 'par-page') {
                sellersTable.page.len(parseInt($(e.target).val(), 10) || 10).draw();
            } else {
                sellersTable.ajax.reload();
            }
        });
    </script>
@endpush
