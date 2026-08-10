@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Language') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Language') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Language') => route('admin.languages.index'),
                __('Edit Language') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Edit Language')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.languages.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.languages.update', $language->id) }}"
                                    enctype="multipart/form-data" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="name"  name="name" label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}" value="{{ old('name', $language->name) }}" required="true"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-select id="code" name="code"
                                                    label="{{ __('Code') }}" class="select2" required="true">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select language') }}" />
                                                        @foreach ($all_languages as $lang)
                                                        <x-admin.select-option :selected="old('code', $language->code) == $lang->code"
                                                            value="{{ $lang->code }}" text="{{ $lang->name }}" />
                                                    @endforeach
                                                </x-admin.form-select>
                                            </div>
                                        </div>
                                        <div class="text-center offset-md-3 col-md-6">
                                            <x-admin.update-button :text="__('Update')" />
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
