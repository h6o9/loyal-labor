@extends('admin.master_layout')

@section('title')
    <title>{{ __('Assign Roles') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Assign Roles') }}" :list="[
            ]" /> 
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Assign Roles to 
                                    Sub Admins') }}</h4>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <script>
                                        // Wait for page to fully load then show toast
                                        window.addEventListener('load', function() {
                                            setTimeout(function() {
                                                toastr.success('{{ session('success') }}');
                                            }, 500);
                                        });
                                    </script>
                                @endif

                                <form method="POST" action="{{ route('admin.assign-roles.assign') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_id">{{ __('Select Sub Admin') }} <span class="text-danger">*</span></label>
                                                <select name="admin_id" id="admin_id" class="form-control" required>
                                                    <option value="">{{ __('Select Admin') }}</option>
                                                    @foreach($admins as $admin)
                                                        <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role_id">{{ __('Select Role') }} <span class="text-danger">*</span></label>
                                                <select name="role_id" id="role_id" class="form-control" required>
                                                    <option value="">{{ __('Select Role') }}</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> {{ __('Assign Role') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Admin Roles -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Current Admin Roles') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="adminRolesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Sub Admin Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Current Roles') }}</th>
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
        $(document).ready(function() {
            initServerDataTable('#adminRolesTable', "{{ route('admin.assign-roles.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'roles_badge', name: 'roles_badge', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            // Edit admin roles (delegated since rows are rendered by the DataTable ajax draws)
            $(document).on('click', '.edit-admin-roles', function() {
                var adminId = $(this).data('admin-id');
                var adminName = $(this).data('admin-name');
                
                // Set the admin in dropdown
                $('#admin_id').val(adminId);
                
                // Clear role dropdown
                $('#role_id').val('');
                
                // Get admin's current role via AJAX
                $.get('{{ route("admin.assign-roles.get", ":adminId") }}'.replace(':adminId', adminId))
                    .done(function(roles) {
                        // Set the role if admin has one
                        if (roles.length > 0) {
                            $('#role_id').val(roles[0]);
                        }
                        
                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $("#admin_id").offset().top - 100
                        }, 500);
                    })
                    .fail(function() {
                        alert('Error loading admin roles');
                    });
            });
        });
    </script>
@endpush
