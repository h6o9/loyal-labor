@extends('admin.master_layout')

@section('title')
    <title>{{ __('Status Update History') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Status Update History') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Status Update History') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Status Update History')" />
                                <div>
                                    <form id="filter" action="javascript:;">
                                        <div class="input-group">
                                            <input class="form-control" name="keyword" type="text"
                                                value="{{ request()->get('keyword') }}"
                                                placeholder="{{ __('Search Id, Type, Name, Address, TrxId etc...') }}">

                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="statusUpdateHistoryTable"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Order') }}</th>
                                                <th>{{ __('From') }}</th>
                                                <th>{{ __('To') }}</th>
                                                <th>{{ __('By') }}</th>
                                                <th>{{ __('Updated At') }}</th>
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
            var statusUpdateHistoryTable = initServerDataTable('#statusUpdateHistoryTable', {
                url: "{{ route('admin.orders.all-status-updates', array_filter(['id' => request()->route('id'), 'type' => request('type')])) }}",
                data: function(d) {
                    d.keyword = $('#filter [name="keyword"]').val();
                }
            }, [
                { data: 'type_badge', name: 'type' },
                { data: 'order_display', name: 'order.order_id', orderable: false, searchable: false },
                { data: 'from_status_label', name: 'from_status' },
                { data: 'to_status_label', name: 'to_status' },
                { data: 'changed_by_name', name: 'change_by', orderable: false, searchable: false },
                { data: 'updated_at_display', name: 'updated_at' },
            ], {
                searching: false,
            });

            $('#filter').on('submit', function(e) {
                e.preventDefault();
                statusUpdateHistoryTable.ajax.reload();
            });
        });
    </script>
@endpush
