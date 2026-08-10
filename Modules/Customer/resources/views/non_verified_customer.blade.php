@extends('admin.master_layout')
@section('title')
    <title>{{ __('Non verified Customers') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Non verified Customers') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Non verified Customers') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="customer_filter_form" action="{{ route('admin.non-verified-customers') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input id="keyword" name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="banned" name="banned">
                                                <x-admin.select-option value="" text="{{ __('Select Banned') }}" />
                                                <x-admin.select-option value="1" :selected="request('banned') == '1'"
                                                    text="{{ __('Banned') }}" />
                                                <x-admin.select-option value="0" :selected="request('banned') == '0'"
                                                    text="{{ __('Non-banned') }}" />
                                            </x-admin.form-select>
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
                        @if ($hasUsers && checkAdminHasPermission('customer.bulk.mail'))
                            <a class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#verifyModal"
                                href="javascript:;">{{ __('Send Verify Link to All') }}</a>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="nonVerifiedCustomersTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Joined at') }}</th>
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

    @if ($hasUsers && checkAdminHasPermission('customer.bulk.mail'))
        <!-- Start Verify modal -->
        <div class="modal fade" id="verifyModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
            tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Send verify link to customer mail') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <p>{{ __('Are you sure want to send verify link to customer mail?') }}</p>

                            <form action="{{ route('admin.send-verify-request-to-all') }}" method="POST">
                                @csrf

                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Send Request') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Verify modal -->
    @endif

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
            var nonVerifiedCustomersTable = initServerDataTable('#nonVerifiedCustomersTable', {
                url: "{{ route('admin.non-verified-customers') }}",
                data: function(d) {
                    d.keyword = $('#keyword').val();
                    d.banned = $('#banned').val();
                    d.order_by = $('#order_by').val();
                }
            }, [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#customer_filter_form').on('submit', function(e) {
                e.preventDefault();
                nonVerifiedCustomersTable.ajax.reload();
            });

            $('#customer_filter_form').on('change', function(e) {
                if (e.target && e.target.id === 'par-page') {
                    nonVerifiedCustomersTable.page.len(parseInt($(e.target).val(), 10) || 10).draw();
                } else {
                    nonVerifiedCustomersTable.ajax.reload();
                }
            });
        });
    </script>
@endpush
