@extends('admin.master_layout')
@section('title')
    <title>View Permissions - {{ $staff->name }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Staff Permissions</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.staff-permissions.index') }}">Staff Permissions</a></div>
                    <div class="breadcrumb-item">{{ $staff->name }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h4>Permissions for {{ $staff->name }}</h4>
                            <small class="text-muted">{{ $staff->email }}</small>
                        </div>
                        <a href="{{ route('admin.staff-permissions.edit', $staff->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit Permissions
                        </a>
                    </div>
                    <div class="card-body">
                        @foreach($modules as $moduleKey => $moduleName)
                            @php
                                $permission = $staff->staffPermissions->where('module', $moduleKey)->first();
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <h5>{{ $moduleName }}</h5>
                                    <small class="text-muted">{{ $moduleKey }}</small>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <i class="fa fa-eye fa-2x {{ $permission && $permission->can_view ? 'text-success' : 'text-muted' }}"></i>
                                                <h6 class="mt-2">Can View</h6>
                                                <span class="badge badge-{{ $permission && $permission->can_view ? 'success' : 'secondary' }}">
                                                    {{ $permission && $permission->can_view ? 'Granted' : 'Denied' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <i class="fa fa-plus fa-2x {{ $permission && $permission->can_create ? 'text-success' : 'text-muted' }}"></i>
                                                <h6 class="mt-2">Can Create</h6>
                                                <span class="badge badge-{{ $permission && $permission->can_create ? 'success' : 'secondary' }}">
                                                    {{ $permission && $permission->can_create ? 'Granted' : 'Denied' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <i class="fa fa-edit fa-2x {{ $permission && $permission->can_edit ? 'text-success' : 'text-muted' }}"></i>
                                                <h6 class="mt-2">Can Edit</h6>
                                                <span class="badge badge-{{ $permission && $permission->can_edit ? 'success' : 'secondary' }}">
                                                    {{ $permission && $permission->can_edit ? 'Granted' : 'Denied' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <i class="fa fa-trash fa-2x {{ $permission && $permission->can_delete ? 'text-success' : 'text-muted' }}"></i>
                                                <h6 class="mt-2">Can Delete</h6>
                                                <span class="badge badge-{{ $permission && $permission->can_delete ? 'success' : 'secondary' }}">
                                                    {{ $permission && $permission->can_delete ? 'Granted' : 'Denied' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.staff-permissions.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
