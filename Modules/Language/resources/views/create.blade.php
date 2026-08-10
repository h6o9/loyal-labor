@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Language') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Language') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Language') => route('admin.languages.index'),
                __('Create Language') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Create Language')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.languages.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.languages.store') }}" enctype="multipart/form-data"
                                    method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="name" name="name"
                                                    label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}"
                                                    value="{{ old('name') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-select id="code" name="code"
                                                    label="{{ __('Code') }}" class="select2" required="true">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select language') }}" />
                                                    @foreach ($all_languages as $language)
                                                        <x-admin.select-option :selected="old('code') == $language->code"
                                                            value="{{ $language->code }}" text="{{ $language->name }}" />
                                                    @endforeach
                                                </x-admin.form-select>
                                            </div>
                                        </div>
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
        </section>
    </div>
@endsection
