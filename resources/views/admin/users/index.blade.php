@extends('admin.master_layout')
@section('title')
    <title>Users</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Users Management</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="type_filter">{{ __('Filter by Type') }}</label>
                                        <select id="type_filter" class="form-control select2">
                                            <option value="">{{ __('All') }}</option>
                                            <option value="user" @selected(request('type') === 'user')>{{ __('Users') }}</option>
                                            <option value="technician" @selected(request('type') === 'technician')>{{ __('Technicians') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="usersTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
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
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "{{ __('Search type...') }}",
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: Infinity
            });

            var usersTable = initServerDataTable('#usersTable', {
                url: "{{ route('admin.users.index') }}",
                data: function(d) {
                    d.type = $('#type_filter').val();
                }
            }, [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'user_type', name: 'user_type' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#type_filter').on('change', function() {
                var url = new URL(window.location.href);
                var type = $(this).val();

                if (type === '') {
                    url.searchParams.delete('type');
                } else {
                    url.searchParams.set('type', type);
                }

                window.history.replaceState({}, '', url.toString());
                usersTable.ajax.reload();
            });
        });
    </script>
@endpush