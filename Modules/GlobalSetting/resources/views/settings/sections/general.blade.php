<div class="tab-pane fade active show" id="general_tab" role="tabpanel">
    <form action="{{ route('admin.update-general-setting') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-input id="app_name" name="app_name" value="{{ $setting->app_name }}" :required=true
                label="{{ __('App Name') }}" />
        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="admin_auth_bg" :required=false div_id="admin_auth_bg_preview"
                label_id="admin_auth_bg_label" input_id="admin_auth_bg_upload" :image="$setting->admin_auth_bg"
                label="{{ __('Admin Login Background') }}" button_label="{{ __('Update Image') }}" />
        </div>

        <div class="form-group">
            <x-admin.form-input id="admin_login_prefix" name="admin_login_prefix"
                value="{{ $setting->admin_login_prefix }}" :required=true label="{{ __('Admin Login Prefix') }}" />
            <p class="mt-2"><span class="text-danger fw-bolder">{{ __('Warning:') }}</span>
                <strong>{{ __('Your login url will be changed to') }}</strong>
                <a id="url_to_change" href="{{ route('admin.login') }}">{{ route('admin.login') }}</a>
            </p>
        </div>

        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
