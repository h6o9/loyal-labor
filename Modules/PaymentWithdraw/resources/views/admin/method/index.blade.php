@extends('admin.master_layout')
@section('title')
    <title>{{ __('Withdraw Method') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Withdraw Method') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Withdraw Method') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="methods_filter_form" action="{{ route('admin.withdraw-method.index') }}" method="GET">
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
                                            <x-admin.form-select class="form-select" id="status" name="status">
                                                <x-admin.select-option value="" text="{{ __('Select Status') }}" />
                                                <x-admin.select-option value="active" :selected="request('status') == 'active'"
                                                    text="{{ __('Active') }}" />
                                                <x-admin.select-option value="inactive" :selected="request('status') == 'inactive'"
                                                    text="{{ __('In-Active') }}" />
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
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Withdraw Method')" />
                                <div>
                                    <x-admin.add-button :href="route('admin.withdraw-method.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="methodsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Minimum Amount') }}</th>
                                                <th>{{ __('Maximum Amount') }}</th>
                                                <th>{{ __('Charge') }}</th>
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
            $("#deleteForm").attr("action", '{{ url('admin/withdraw-method/') }}' + "/" + id)
        }

        "use strict"

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/withdraw-method/status-update') }}" + "/" + id,
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

        var methodsTable = initServerDataTable('#methodsTable', {
            url: "{{ route('admin.withdraw-method.index') }}",
            data: function(d) {
                d.keyword = $('#keyword').val();
                d.status = $('#status').val();
                d.order_by = $('#order_by').val();
            }
        }, [
            { data: 'name', name: 'name' },
            { data: 'min_amount_col', name: 'min_amount' },
            { data: 'max_amount_col', name: 'max_amount' },
            { data: 'charge_col', name: 'withdraw_charge' },
            { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ], {
            drawCallback: function() {
                // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
            }
        });

        $('#methods_filter_form').on('submit', function(e) {
            e.preventDefault();
            methodsTable.ajax.reload();
        });

        $('#methods_filter_form').on('change', function(e) {
            if (e.target && e.target.id === 'par-page') {
                methodsTable.page.len(parseInt($(e.target).val(), 10) || 10).draw();
            } else {
                methodsTable.ajax.reload();
            }
        });
    </script>
@endsection
