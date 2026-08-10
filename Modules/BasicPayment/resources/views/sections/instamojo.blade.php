<div class="tab-pane fade" id="instamojo_tab" role="tabpanel">
    <form action="{{ route('admin.instamojo-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-select class="form-select" id="instamojo_currency_id" name="instamojo_currency_id"
                    label="{{ __('Currency Name') }}">
                    <x-admin.select-option value="" text="{{ __('Select Currency') }}" />
                    @foreach ($currencies as $currency)
                        <x-admin.select-option value="{{ $currency->id }}" :selected="$payment_setting->instamojo_currency_id == $currency->id"
                            text="{{ $currency->currency_name }}" />
                    @endforeach
                </x-admin.form-select>
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-input id="instamojo_charge" name="instamojo_charge"
                    value="{{ $payment_setting->instamojo_charge }}" label="{{ __('Gateway charge') }}(%)" />
            </div>
            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="instamojo_client_id" name="instamojo_client_id"
                        value="instamojo-test-348949439-api-key" label="{{ __('Client ID') }}" required="true" />
                @else
                    <x-admin.form-input id="instamojo_client_id" name="instamojo_client_id"
                        value="{{ $payment_setting->instamojo_client_id }}" label="{{ __('Client ID') }}"
                        required="true" />
                @endif
            </div>

            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="instamojo_client_secret" name="instamojo_client_secret"
                        value="instamojo-test-348949439-auth-token" label="{{ __('Client Secret') }}" required="true" />
                @else
                    <x-admin.form-input id="instamojo_client_secret" name="instamojo_client_secret"
                        value="{{ $payment_setting->instamojo_client_secret }}" label="{{ __('Client Secret') }}"
                        required="true" />
                @endif
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-select class="form-select" id="instamojo_account_mode" name="instamojo_account_mode"
                    label="{{ __('Account Mode') }}" required="true">
                    <x-admin.select-option value="live" :selected="$payment_setting->instamojo_account_mode == 'live'" text="{{ __('Live') }}" />
                    <x-admin.select-option value="sandbox" :selected="$payment_setting->instamojo_account_mode == 'sandbox'" text="{{ __('Sandbox') }}" />
                </x-admin.form-select>
            </div>

        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="instamojo_image" div_id="instamojo_image_preview"
                label_id="instamojo_image_label" input_id="instamojo_image_upload" :image="$payment_setting->instamojo_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="instamojo_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$payment_setting->instamojo_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
