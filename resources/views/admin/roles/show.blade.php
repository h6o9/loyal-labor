@extends('admin.master_layout')
@section('title')
    <title>{{ __('Role Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Role Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Admin Settings') => '#',
                __('Manage Roles') => route('admin.role.index'),
                __('Role Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Role Details')" />
                                <div>
                                    @adminCan('role.view')
                                        <x-admin.back-button :href="route('admin.role.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Role Name') }}</label>
                                            <p class="form-control-plaintext">{{ ucfirst($role->name) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Guard Name') }}</label>
                                            <p class="form-control-plaintext">{{ $role->guard_name }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Permissions') }}</label>
                                            <div class="row">
                                                @if($role->permissions->count() > 0)
                                                    @foreach($role->permissions as $permission)
                                                        <div class="col-md-4 mb-2">
                                                            <span class="badge badge-info">{{ $permission->name }}</span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted">{{ __('No permissions assigned to this role.') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Created At') }}</label>
                                            <p class="form-control-plaintext">{{ $role->created_at->format('d M Y H:i A') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="btn-group">
                                            @adminCan('role.edit')
                                                <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> {{ __('Edit Role') }}
                                                </a>
                                            @endadminCan
                                            @adminCan('role.delete')
                                                <form action="{{ route('admin.role.destroy', $role->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this role?') }}')">
                                                        <i class="fas fa-trash"></i> {{ __('Delete Role') }}
                                                    </button>
                                                </form>
                                            @endadminCan
                                            <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> {{ __('Back to Roles') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
