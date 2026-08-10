@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Kyc') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Manage Kyc') }}</h1>
            </div>

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Document') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Document Name') }}</th>
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
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('admin/delete-kyc-info/') }}" + "/" + id)
        }

        initServerDataTable('#dataTable', "{{ route('admin.kyc-list.index') }}", [
            { data: 'document', name: 'document', orderable: false, searchable: false },
            { data: 'name_col', name: 'shop.shop_name', orderable: false },
            { data: 'type_name', name: 'type.name', orderable: false },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]);
    </script>
@endpush
