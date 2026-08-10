@extends('admin.master_layout')
@section('title')
    <title>{{ __('Assign Permissions to Role') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Assign Permissions to Role') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Admin Settings') => '#',
                __('Assign Permissions') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Assign Permissions to Role')" />
                                <div>
                                    @adminCan('role.view')
                                        <a href="{{ url('/admin/role') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                        </a>
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="permissionForm" action="{{ url('/update-role-permissions') }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="role_id">{{ __('Select Role') }} <span class="text-danger">*</span></label>
                                            <select name="user_id" id="role_id" class="form-control select2" required>
                                                <option value="" disabled selected>{{ __('Select Role') }}</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                            <div id="permissionsLoadingIndicator" class="mt-2" style="display: none;">
                                                <i class="fas fa-spinner fa-spin"></i> <span>{{ __('Loading permissions...') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4" id="permissionsSection" >
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <label class="form-label font-weight-bold">{{ __('Admin Panel Menu Permissions') }}</label>
                                                    <div>
                                                        <button type="button" id="checkAll" class="btn btn-sm btn-primary">Check All</button>
                                                        <button type="button" id="uncheckAll" class="btn btn-sm btn-secondary">Uncheck All</button>
                                                    </div>
                                                </div>
                                                <div class="admin_role_border">
                                                    <div class="row">
                                                        @php $i=1; @endphp
                                                        @foreach ($permission_groups as $groupName => $permissions)
                                                            @php
                                                                // Filter permissions to hide unwanted ones
                                                                $unwantedPermissions = [
                                                                    'shop.create',
                                                                    'add_shop',
                                                                    'view_shop_list',
                                                                    'shop-management.view-staff-permissions',
                                                                    'staff.status',
                                                                    'shop-management.view',
                                                                    'shop-management.assign',
                                                                    'shop-management.view-staff-permissions',
                                                                    'admin profile.view',
                                                                    'admin profile.update',
                                                                    'admin.profile.view',
                                                                    'admin.profile.update',
                                                                    'admin_profile_view',
                                                                    'admin_profile_update'
                                                                ];
                                                                
                                                                $filteredPermissions = $permissions->filter(function($permission) use ($unwantedPermissions) {
                                                                    return !in_array($permission->name, $unwantedPermissions) && 
                                                                           !str_contains($permission->name, 'profile') &&
                                                                           !str_contains($permission->name, 'shop-management.assign') &&
                                                                           !str_contains($permission->name, 'staff.status');
                                                                });
                                                            @endphp
                                                            
                                                            @if(!empty($groupName) && $filteredPermissions->count() > 0)
                                                            @php
                                                                // Map group names to display labels
                                                                $displayGroupName = $groupName;
                                                                if (trim($groupName) == 'staff') {
                                                                    $displayGroupName = 'Staff permission';
                                                                } elseif (trim($groupName) == 'admin') {
                                                                    $displayGroupName = 'Sub Admin';
                                                                } elseif (trim($groupName) == 'admin profile') {
                                                                    continue; // Skip admin profile group entirely
                                                                } elseif (trim($groupName) == 'shop-management') {
                                                                    continue; // Skip shop-management group entirely
                                                                }
                                                            @endphp
                                                            <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input section-checkbox" 
                                                                                   type="checkbox" 
                                                                                   id="section_{{ $i }}" 
                                                                                   data-section="{{ Str::slug($groupName) }}">
                                                                            <label for="section_{{ $i }}" class="form-check-label text-capitalize fw-bold">
                                                                                <i class="fas fa-folder"></i> {{ $displayGroupName }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 permission-group-{{ $i }}">
                                                                        @foreach ($filteredPermissions as $permission)
                                                                            <div class="form-check mb-2">
                                                                                <input name="permissions[]" 
                                                                                       class="form-check-input permission-checkbox" 
                                                                                       type="checkbox" 
                                                                                       id="perm_{{ $permission->id }}" 
                                                                                       value="{{ $permission->name }}" 
                                                                                       data-section="{{ Str::slug($groupName) }}">
                                                                                <label for="perm_{{ $permission->id }}" class="form-check-label">
                                                                                    {{ ucfirst(str_replace('.', ' ', $permission->name)) }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @php $i++; @endphp
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4" id="submitSection" style="display: none;">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                                <i class="fas fa-save"></i> <span class="btn-text">{{ __('Save Permissions') }}</span>
                                                <span class="btn-loader" style="display: none;">
                                                    <i class="fas fa-spinner fa-spin"></i> {{ __('Loading...') }}
                                                </span>
                                            </button>
                                            <a href="{{ url('/admin/role') }}" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> {{ __('Cancel') }}
                                            </a>
                                        </div>
                                    </div>
                                </form>
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
            // Build section mapping dynamically
            var sectionMapping = {};
            @php $i=1; @endphp
            @foreach ($permission_groups as $groupName => $permissions)
                @php
                    $unwantedPermissions = [
                        'shop.create', 'add_shop', 'view_shop_list',
                        'shop-management.view-staff-permissions', 'staff.status',
                        'shop-management.view', 'shop-management.assign',
                        'admin profile.view', 'admin profile.update',
                        'admin.profile.view', 'admin.profile.update'
                    ];
                    $filteredPermissions = $permissions->filter(function($permission) use ($unwantedPermissions) {
                        return !in_array($permission->name, $unwantedPermissions) && 
                               !str_contains($permission->name, 'profile') &&
                               !str_contains($permission->name, 'shop-management.assign') &&
                               !str_contains($permission->name, 'staff.status');
                    });
                @endphp
                @if(!empty($groupName) && $filteredPermissions->count() > 0 && !in_array(trim($groupName), ['admin profile', 'shop-management']))
                    var sectionKey = '{{ Str::slug($groupName) }}';
                    var sectionClass = '.permission-group-{{ $i }}';
                    sectionMapping[sectionKey] = sectionClass;
                @endif
            @php $i++; @endphp
            @endforeach

            // Function to update section checkbox based on individual permissions
            function updateSectionCheckbox(section) {
                const sectionClass = sectionMapping[section];
                if (sectionClass) {
                    const totalPermissions = $(sectionClass + ' .permission-checkbox').length;
                    const checkedPermissions = $(sectionClass + ' .permission-checkbox:checked').length;
                    const sectionCheckbox = $('.section-checkbox[data-section="' + section + '"]');
                    sectionCheckbox.prop('checked', totalPermissions === checkedPermissions && totalPermissions > 0);
                }
            }

            // Handle section checkbox click
            $('.section-checkbox').on('change', function() {
                const section = $(this).data('section');
                const sectionClass = sectionMapping[section];
                const isChecked = $(this).prop('checked');
                
                if (sectionClass) {
                    $(sectionClass + ' .permission-checkbox').prop('checked', isChecked);
                }
            });

            // Handle individual permission checkbox click
            $(document).on('change', '.permission-checkbox', function() {
                const section = $(this).data('section');
                updateSectionCheckbox(section);
            });

            // Check All button functionality
            $('#checkAll').on('click', function() {
                $('.permission-checkbox').prop('checked', true);
                $('.section-checkbox').prop('checked', true);
            });

            // Uncheck All button functionality
            $('#uncheckAll').on('click', function() {
                $('.permission-checkbox').prop('checked', false);
                $('.section-checkbox').prop('checked', false);
            });

            // Load role permissions when role is selected
            $('#role_id').on('change', function() {
                var roleId = $(this).val();
                if (roleId) {
                    // Hide permissions section and submit section
                    $('#permissionsSection, #submitSection').hide();
                    // Show loading indicator
                    $('#permissionsLoadingIndicator').fadeIn();
                    
                    $.ajax({
                        url: '{{ url("/get-role-permissions") }}/' + roleId,
                        type: 'GET',
                        success: function(response) {
                            // Hide loading indicator
                            $('#permissionsLoadingIndicator').fadeOut();
                            
                            if (response.success) {
                                $('.permission-checkbox').prop('checked', false);
                                $('.section-checkbox').prop('checked', false);
                                
                                // Always check dashboard permissions by default
                                $('.permission-checkbox[value="dashboard.view"]').prop('checked', true);
                                
                                if (response.permissions && response.permissions.length > 0) {
                                    $.each(response.permissions, function(index, permissionName) {
                                        // Only check if permission exists in DOM (not filtered out)
                                        if ($('.permission-checkbox[value="' + permissionName + '"]').length > 0) {
                                            $('.permission-checkbox[value="' + permissionName + '"]').prop('checked', true);
                                        }
                                    });
                                }
                                
                                setTimeout(function() {
                                    for (const section in sectionMapping) {
                                        updateSectionCheckbox(section);
                                    }
                                }, 100);
                                
                                $('#permissionsSection, #submitSection').fadeIn();
                            } else {
                                toastr.error(response.message || 'Error loading permissions');
                                $('#permissionsSection, #submitSection').hide();
                            }
                        },
                        error: function(xhr) {
                            // Hide loading indicator on error
                            $('#permissionsLoadingIndicator').fadeOut();
                            console.error('Error:', xhr);
                            toastr.error('Error loading role permissions');
                            $('#permissionsSection, #submitSection').hide();
                        }
                    });
                } else {
                    $('#permissionsSection, #submitSection').fadeOut();
                    $('#permissionsLoadingIndicator').fadeOut();
                    $('.permission-checkbox').prop('checked', false);
                    $('.section-checkbox').prop('checked', false);
                }
            });

            // Form submission with loading state
            $('#permissionForm').on('submit', function(e) {
                e.preventDefault(); // Prevent normal form submission
                
                // Get the save button elements
                var $saveBtn = $('#saveBtn');
                var $btnText = $saveBtn.find('.btn-text');
                var $btnLoader = $saveBtn.find('.btn-loader');
                var $btnIcon = $saveBtn.find('.fa-save');
                
                // Disable button and show loading state
                $saveBtn.prop('disabled', true);
                $btnText.hide();
                $btnIcon.hide();
                $btnLoader.show();
                
                // Submit via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Show success toast without "Success" title
                            toastr.success(response.message, '', {
                                onHidden: function() {
                                    // Reset button after toast message is hidden
                                    resetButton();
                                }
                            });
                        } else {
                            toastr.error(response.message || 'Something went wrong', '', {
                                onHidden: function() {
                                    resetButton();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Something went wrong';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage, '', {
                            onHidden: function() {
                                resetButton();
                            }
                        });
                    }
                });
                
                function resetButton() {
                    // Reset button to original state
                    $saveBtn.prop('disabled', false);
                    $btnLoader.hide();
                    $btnText.show();
                    $btnIcon.show();
                }
                
                return false;
            });
        });
    </script>
@endpush