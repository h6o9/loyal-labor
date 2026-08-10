@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Sub Admin') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Sub Admins') }}" :list="[
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Manage Sub Admins')" />
                                <small class="font-weight-bold text-danger">(The default password for all sub-admins is 12345678. This password is automatically generated when a new sub-admin is created. The Admin Panel will only be accessible to a sub-admin once a role has been assigned to them.)</small>
                                <div>
                                    @adminCan('admin.create')
                                        <x-admin.add-button :href="route('admin.admin.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="adminTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
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
    @adminCan('admin.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var adminTable = initServerDataTable('#adminTable', {
                url: "{{ route('admin.admin.index') }}"
            }, [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                drawCallback: function () {
                    // Destroy existing toggles and re-initialize
                    $('.toggle-switch').each(function() {
                        var $this = $(this);
                        if ($this.data('bootstrapToggle')) {
                            $this.bootstrapToggle('destroy');
                        }
                        $this.bootstrapToggle({
                            on: 'Active',
                            off: 'Inactive',
                            onstyle: 'success',
                            offstyle: 'danger',
                            size: 'small'
                        });
                    });
                }
            });
        });

        // Function to change admin status
        function changeAdminStatus(id) {
            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}";
            var statusUrl = "{{ url('/admin/admin-status') }}/" + id;

            if (isDemo == 'DEMO') {
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }

            $.ajax({
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
                },
                url: statusUrl,
                success: function(response) {
                    toastr.success(response.message);
                    // Reload the table to update status
                    $('#adminTable').DataTable().ajax.reload(null, false);
                },
                error: function(err) {
                    toastr.error((err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Failed to update status');
                    console.log(err);
                }
            });
        }
    </script>
@endpush	