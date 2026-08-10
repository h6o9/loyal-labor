@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="withdraws_filter_form" action="{{ url()->current() }}" method="GET">
                                    <div class="row">
                                        <div
                                            class="{{ Route::is('admin.withdraw-list') ? 'col-md-4' : 'col-lg-3' }} form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input id="keyword" name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        @if (Route::is('admin.withdraw-list'))
                                            <div class="col-md-4 col-lg-3 form-group mb-3">
                                                <x-admin.form-select class="form-select" id="status" name="status">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Status') }}" />
                                                    <x-admin.select-option value="pending" :selected="request('status') == 'pending'"
                                                        text="{{ __('Pending') }}" />
                                                    <x-admin.select-option value="approved" :selected="request('status') == 'approved'"
                                                        text="{{ __('Approved') }}" />
                                                    <x-admin.select-option value="rejected" :selected="request('status') == 'rejected'"
                                                        text="{{ __('Rejected') }}" />
                                                </x-admin.form-select>
                                            </div>
                                        @endif

                                        <div class="col-md-4 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="select2" id="user" name="user">
                                                <x-admin.select-option value="" text="{{ __('Select user') }}" />
                                                @foreach ($users as $user)
                                                    <x-admin.select-option value="{{ $user->id }}" :selected="$user->id == request('user')"
                                                        text="{{ $user->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-3 form-group mb-3">
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
                                    <table class="table table-striped" id="withdrawsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('User') }}</th>
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

    <x-admin.delete-modal />
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/delete-withdraw/') }}' + "/" + id)
        }
    </script>
@endsection

@push('js')
    <script>
        "use strict";

        var withdrawsTable = initServerDataTable('#withdrawsTable', {
            url: "{{ url()->current() }}",
            data: function(d) {
                d.keyword = $('#keyword').val();
                d.status = $('#status').val();
                d.user = $('#user').val();
                d.order_by = $('#order_by').val();
            }
        }, [
            { data: 'user_name', name: 'user.name', orderable: false, searchable: false },
            { data: 'method', name: 'method' },
            { data: 'charge_col', name: 'charge_col', orderable: false, searchable: false },
            { data: 'total_amount_col', name: 'total_amount' },
            { data: 'withdraw_amount_col', name: 'withdraw_amount' },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]);

        $('#withdraws_filter_form').on('submit', function(e) {
            e.preventDefault();
            withdrawsTable.ajax.reload();
        });

        $('#withdraws_filter_form').on('change', function(e) {
            if (e.target && e.target.id === 'par-page') {
                withdrawsTable.page.len(parseInt($(e.target).val(), 10) || 10).draw();
            } else {
                withdrawsTable.ajax.reload();
            }
        });
    </script>
@endpush
