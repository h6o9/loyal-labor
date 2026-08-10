<div class="tab-pane fade" id="social_login_tab" role="tabpanel">
    <form action="{{ route('admin.update-social-login') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-input id="google_client_id" name="google_client_id" value="{{ $setting->google_client_id }}"
                label="{{ __('Google Client ID') }}" />
        </div>
        <div class="form-group">
            <x-admin.form-input id="google_secret_id" name="google_secret_id" value="{{ $setting->google_secret_id }}"
                label="{{ __('Google Secret ID') }}" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="google_login_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$setting->google_login_status == 'active'" />
        </div>
        <x-admin.update-button :text="__('Update')" />

    </form>

    <div class="form-group mt-3">
        <label>{{ __('Google Redirect Url') }} <span class="fa fa-info-circle text--primary" data-toggle="tooltip"
                data-placement="top"
                title="{{ __('Copy the Gmail login URL and paste it wherever you need to use it.') }}"></span></label>
        <div class="input-group mb-3">
            <input class="form-control" id="gmail_redirect_url" type="text"
                value="{{ route('auth.social.callback', \App\Enums\SocialiteDriverType::GOOGLE->value) }}" readonly>
            <x-admin.button id="copyButton" text="{{ __('Click to copy') }}" />
        </div>
    </div>
</div>
