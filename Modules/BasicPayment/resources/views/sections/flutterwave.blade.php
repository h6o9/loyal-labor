<div class="tab-pane fade" id="flutterwave_tab" role="tabpanel">
    <form action="{{ route('admin.flutterwave-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-select id="flutterwave_currency_id" name="flutterwave_currency_id"
                    label="{{ __('Currency Name') }}" class="form-select">
                    <x-admin.select-option value="" text="{{ __('Select Currency') }}" />
                    @foreach ($currencies as $currency)
                        <x-admin.select-option :selected="$payment_setting->flutterwave_currency_id == $currency->id" value="{{ $currency->id }}"
                            text="{{ $currency->currency_name }}" />
                    @endforeach
                </x-admin.form-select>
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-input id="flutterwave_charge" name="flutterwave_charge"
                    label="{{ __('Gateway charge') }}(%)" value="{{ $payment_setting->flutterwave_charge }}" />
            </div>
            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="flutterwave_public_key" name="flutterwave_public_key"
                        label="{{ __('Public key') }}" value="Flutterwave-test-348949439-public-key" required="true" />
                @else
                    <x-admin.form-input id="flutterwave_public_key" name="flutterwave_public_key"
                        label="{{ __('Public key') }}" value="{{ $payment_setting->flutterwave_public_key }}"
                        required="true" />
                @endif
            </div>

            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="flutterwave_secret_key" name="flutterwave_secret_key"
                        label="{{ __('Secret key') }}" value="Flutterwave-test-8384934-key-secret" required="true" />
                @else
                    <x-admin.form-input id="flutterwave_secret_key" name="flutterwave_secret_key"
                        label="{{ __('Secret key') }}" value="{{ $payment_setting->flutterwave_secret_key }}"
                        required="true" />
                @endif
            </div>

            <div class="form-group col-md-12">
                <x-admin.form-input id="flutterwave_app_name" name="flutterwave_app_name"
                    label="{{ __('Flutterwave App Name') }}" value="{{ $payment_setting->flutterwave_app_name }}"
                    required="true" />
            </div>

        </div>

        <div class="form-group">
            <x-admin.form-image-preview div_id="flutterwave_image_preview" label_id="flutterwave_image_label"
                input_id="flutterwave_image_upload" :image="$payment_setting->flutterwave_image" name="flutterwave_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="flutterwave_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$payment_setting->flutterwave_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>