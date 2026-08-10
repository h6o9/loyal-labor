@extends('admin.master_layout')
@section('title')
    <title>Edit Permissions - {{ $staff->name }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Staff Permissions</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.staff-permissions.index') }}">Staff Permissions</a></div>
                    <div class="breadcrumb-item">Edit - {{ $staff->name }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Permissions - {{ $staff->name }}</h4>
                        <small class="text-muted">{{ $staff->email }}</small>
                    </div>
                    <form method="POST" action="{{ route('admin.staff-permissions.update', $staff->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @foreach($modules as $moduleKey => $moduleName)
                                @php
                                    $permission = $staff->staffPermissions->where('module', $moduleKey)->first();
                                @endphp
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <h5 class="mt-2">{{ $moduleName }}</h5>
                                        <small class="text-muted">{{ $moduleKey }}</small>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[{{ $moduleKey }}][can_view]" value="1" id="{{ $moduleKey }}_can_view" {{ $permission && $permission->can_view ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $moduleKey }}_can_view">
                                                        Can View
                                                    </label>
                                                </div>
                                            </div>
                                            @if($moduleKey != 'my_jobs')
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[{{ $moduleKey }}][can_create]" value="1" id="{{ $moduleKey }}_can_create" {{ $permission && $permission->can_create ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $moduleKey }}_can_create">
                                                        Can Create
                                                    </label>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[{{ $moduleKey }}][can_edit]" value="1" id="{{ $moduleKey }}_can_edit" {{ $permission && $permission->can_edit ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $moduleKey }}_can_edit">
                                                        Can Edit
                                                    </label>
                                                </div>
                                            </div>
                                            @if($moduleKey != 'my_jobs')
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[{{ $moduleKey }}][can_delete]" value="1" id="{{ $moduleKey }}_can_delete" {{ $permission && $permission->can_delete ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $moduleKey }}_can_delete">
                                                        Can Delete
                                                    </label>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @endforeach

                            <!-- ========================================== -->
                            <!-- JOB ASSIGN SECTION - COMES AFTER ALL MODULES -->
                            <!-- ========================================== -->
                            @if(isset($modules['shop_management']))
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <hr class="my-4" style="border-top: 2px solid #007bff;">
                                        <div class="job-assign-section" style="border-left: 3px solid #007bff; padding-left: 15px; background: #f8f9fc; border-radius: 5px;">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="mb-0" style="color: #007bff; font-weight: 600;">
                                                    <i class="fas fa-tasks"></i> 📋 Job Assign Configuration
                                                </h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @php
                                                        $shopPermission = $staff->staffPermissions->where('module', 'shop_management')->first();
                                                    @endphp
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="permissions[shop_management][permissable]" 
                                                               value="1" 
                                                               id="shop_management_permissable" 
                                                               {{ $shopPermission && $shopPermission->permissable ? 'checked' : '' }}>
                                                        <label class="form-check-label font-weight-bold" for="shop_management_permissable" style="color: #28a745;">
                                                            <i class="fas fa-user-check"></i> Permissable
                                                        </label>
                                                    </div>
                                                    <small class="text-danger d-block mt-1" id="permissableHelpText">
                                                        <i class="fas fa-info-circle"></i> 
                                                        @if($shopPermission && $shopPermission->permissable)
                                                            ✅ Permissable enabled - Staff member details will appear in assign modal within shop management section
                                                        @else
                                                            ⚠️ Note: By checking this box, the staff member's email will be displayed in the assign modal within the shop management section.
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Permissions
                            </button>
                            <a href="{{ route('admin.staff-permissions.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Quick select all permissions for a module
            $('.select-all').click(function() {
                const moduleKey = $(this).data('module');
                $(`input[name^="permissions[${moduleKey}]"]`).prop('checked', true);
            });

            // Clear all permissions for a module
            $('.clear-all').click(function() {
                const moduleKey = $(this).data('module');
                $(`input[name^="permissions[${moduleKey}]"]`).prop('checked', false);
            });

            // Dynamic behavior for permissable checkbox
            $('#shop_management_permissable').on('change', function() {
                if($(this).is(':checked')) {
                    $('#permissableHelpText').html('<i class="fas fa-check-circle"></i> ✅ Permissable enabled - Staff member details will appear in assign modal within shop management section');
                    $('#permissableHelpText').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#permissableHelpText').html('<i class="fas fa-info-circle"></i> ⚠️ Note: By checking this box, the staff member\'s email will be displayed in the assign modal within the shop management section.');
                    $('#permissableHelpText').removeClass('text-success').addClass('text-danger');
                }
            });
        });
    </script>
@endpush