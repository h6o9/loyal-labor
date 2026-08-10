<div class="tab-pane fade active show" id="google_recaptcha_tab" role="tabpanel">
    <form action="{{ route('admin.update-google-captcha') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-input id="recaptcha_site_key" name="recaptcha_site_key"
                value="{{ $setting->recaptcha_site_key }}" label="{{ __('Captcha Site Key') }}" :title="__('Enter your Google Captcha Site Key version 3')" />
        </div>

        <div class="form-group">
            <x-admin.form-input id="recaptcha_secret_key" name="recaptcha_secret_key"
                value="{{ $setting->recaptcha_secret_key }}" label="{{ __('Captcha Secret Key') }}" :title="__('Enter your Google Captcha Secret Key version 3')" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="recaptcha_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$setting->recaptcha_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
