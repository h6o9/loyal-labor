@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Role') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Role') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Admin Settings') => '#',
                __('Manage Roles') => route('admin.role.index'),
                __('Edit Role') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Edit Role')" />
                                <div>
                                    @adminCan('role.view')
                                        <x-admin.back-button :href="route('admin.role.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <form role="form" action="{{ route('admin.role.update', $role->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">{{ __('Role Name') }} <span class="text-danger">*</span></label>
                                                <input name="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="role_name" placeholder="{{ __('Enter role name') }}" 
                                                    value="{{ old('name', $role->name) }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback"
                                                        role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> {{ __('Update Role') }}
                                            </button>
                                            <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">
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
