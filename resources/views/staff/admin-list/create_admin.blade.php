@extends('admin.master_layout')
@section('title')
    <title>{{ __('Add Admin') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Add Admin') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Admin') => route('admin.admin.index'),
                __('Add Admin') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Add Admin')" />
                                <div>
                                    @adminCan('admin.view')
                                        <x-admin.back-button :href="route('admin.admin.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{ route('admin.admin.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <x-admin.form-input id="name" name="name"
                                                        label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}"
                                                        value="{{ old('name') }}" required="true" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <x-admin.form-input type="email" id="email" name="email"
                                                        label="{{ __('Email') }}" placeholder="{{ __('Enter Email') }}"
                                                        value="{{ old('email') }}" required="true" />
                                                </div>
                                                <div class="form-group col-6">
                                                    <x-admin.form-input type="password" id="password" name="password"
                                                        label="{{ __('Password') }}"
                                                        placeholder="{{ __('Enter Password') }}"
                                                        value="{{ old('password') }}" required="true" />
                                                </div>

                                                <div class="form-group col-6">
                                                    <x-admin.form-select id="role" name="role[]"
                                                        label="{{ __('Assign Role') }}" class="select2" required="true" multiple>
                                                        <x-admin.select-option value="" disabled text="{{ __('Select Role') }}" />
                                                        @foreach ($roles as $role)
                                                            <x-admin.select-option value="{{ $role->name }}" text="{{ $role->name }}" />
                                                        @endforeach
                                                    </x-admin.form-select>
                                                </div>

                                                <div class="form-group col-12">
                                                    <x-admin.form-switch name="status" label="{{ __('status') }}"
                                                        active_value="active" inactive_value="inactive" :checked="old('status') == 'active'" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <x-admin.save-button :text="__('Save')" />
                                                </div>
                                            </div>
                                        </form>
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
