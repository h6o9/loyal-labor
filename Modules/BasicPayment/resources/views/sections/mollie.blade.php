<div class="tab-pane fade" id="mollie_tab" role="tabpanel">
    <form action="{{ route('admin.mollie-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-select id="mollie_currency_id" name="mollie_currency_id"
                    label="{{ __('Currency Name') }}" class="form-select">
                    <x-admin.select-option value="" text="{{ __('Select Currency') }}" />
                    @foreach ($currencies as $currency)
                        <x-admin.select-option :selected="$payment_setting->mollie_currency_id == $currency->id" value="{{ $currency->id }}"
                            text="{{ $currency->currency_name }}" />
                    @endforeach
                </x-admin.form-select>
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-input id="mollie_charge" name="mollie_charge" label="{{ __('Gateway charge') }}(%)"
                    value="{{ $payment_setting->mollie_charge }}" />
            </div>

            <div class="form-group col-md-12">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="mollie_key" name="mollie_key" label="{{ __('Mollie key') }}"
                        value="mollie-test-348949439-key" required="true" />
                @else
                    <x-admin.form-input id="mollie_key" name="mollie_key" label="{{ __('Mollie key') }}"
                        value="{{ $payment_setting->mollie_key }}" required="true" />
                @endif
            </div>

        </div>

        <div class="form-group">
            <x-admin.form-image-preview div_id="mollie_image_preview" label_id="mollie_image_label"
                input_id="mollie_image_upload" :image="$payment_setting->mollie_image" name="mollie_image" label="{{ __('Existing Image') }}"
                button_label="{{ __('Update Image') }}" required="0" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="mollie_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$payment_setting->mollie_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>