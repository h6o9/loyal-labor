@extends('admin.master_layout')

@section('title')
    <title>{{ __('Manage Kyc Type') }}</title>
@endsection

@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Manage Kyc Type') }}</h1>
            </div>

            <div class="section-body">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_coupon_id" href="javascript:;"><i
                        class="fas fa-plus"></i> {{ __('Add New') }}</a>
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Description') }}</th>
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

    <!-- Modal -->
    <div class="modal fade" id="canNotDeleteModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {{ __('You can not delete this Plan. Because there are one or more Plan has been Purcheced.') }}
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_kyc_type_modal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update Kyc Type') }}</h5>
                    <button class="btn btn-danger" data-dismiss="modal" type="button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <form id="update-kyc-type-form" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit_kyc_type_name">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input class="form-control" id="edit_kyc_type_name" name="name" type="text"
                                autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_kyc_type_description">{{ __('Description') }}</label>
                            <input class="form-control" id="edit_kyc_type_description" name="description" type="text"
                                autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_kyc_type_status" name="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-0">
                    <button class="btn btn-danger" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                    <button class="btn btn-primary" form="update-kyc-type-form"
                        type="submit">{{ __('Update') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="create_coupon_id" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create kyc Type') }}</h5>
                    <button class="btn btn-danger" data-dismiss="modal" type="button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('admin.kyc.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" type="text" required autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="">{{ __('Description') }}</label>
                                <input class="form-control" name="description" type="text" value=""
                                    autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="" name="status">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/kyc/') }}' + "/" + id)
        }

        function editKycType(id, name, description, status) {
            var url = '{{ route('admin.kyc.update', ':id') }}'.replace(':id', id);
            $('#update-kyc-type-form').attr('action', url);
            $('#edit_kyc_type_name').val(name);
            $('#edit_kyc_type_description').val(description);
            $('#edit_kyc_type_status').val(String(status));
            $('#edit_kyc_type_modal').modal('show');
        }

        initServerDataTable('#dataTable', "{{ route('admin.kyc.index') }}", [
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]);
    </script>
@endpush
