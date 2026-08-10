@extends('staff.master_layout')
@section('title')
    <title>{{ __('Edit Profile') }}</title>
@endsection
@section('staff-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Profile') }}" :list="[
                __('Dashboard') => route('staff.dashboard'),
                __('Edit Profile') => '#',
            ]" />

            {{-- edit profile area  --}}
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card profile-widget">
                            <div class="profile-widget-description">
                                <form action="{{ route('staff.profile-update') }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <x-admin.form-image-preview :image="!empty($staffUser->image) ? $staffUser->image : $setting->default_avatar" label="{{ __('Existing Image') }}"
                                                button_label="{{ __('Update Image') }}" />
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-input id="name" name="name" label="{{ __('Name') }}"
                                                value="{{ $staffUser->name }}" required="true" />
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-input type="email" id="email" name="email"
                                                label="{{ __('Email') }}" value="{{ $staffUser->email }}" required="true" />
                                        </div>
                                    </div>
                                                                            <div class="row">
                                            <div class="col-12">
                                                <x-admin.update-button :text="__('Update')" />
                                            </div>
                                        </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- edit profile area  --}}

            {{-- edit password area --}}

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <form action="{{ route('staff.update-password') }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">

                                        <div class="form-group col-12">
                                            <x-admin.form-input type="password" id="current_password" name="current_password" label="{{ __('Current Password') }}" required="true" />
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-input type="password" id="password" name="password" label="{{ __('New Password') }}" required="true" />
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-input type="password" id="password_confirmation" name="password_confirmation" label="{{ __('Confirm Password') }}" required="true" />
                                        </div>

                                    </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <x-admin.update-button id="update-btn-2" :text="__('Update')" />
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- edit password area --}}

        </section>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
    </script>
@endpush
