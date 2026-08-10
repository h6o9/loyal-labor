<div class="tab-pane fade" id="sslcommerz_tab" role="tabpanel">
    <form action="{{ route('admin.sslcommerz-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-input id="sslcommerz_store_id" name="sslcommerz_store_id"
                    value="{{ $payment_setting->sslcommerz_store_id }}" label="{{ __('Store ID') }}" />
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-input id="sslcommerz_store_password" name="sslcommerz_store_password"
                    value="{{ $payment_setting->sslcommerz_store_password }}" label="{{ __('Store Password') }}" />
            </div>

            <div class="form-group col-md-12">
                <x-admin.form-input id="sslcommerz_charge" name="sslcommerz_charge"
                    value="{{ $payment_setting->sslcommerz_charge }}" label="{{ __('Gateway charge') }}(%)" />
            </div>

        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="sslcommerz_image" div_id="sslcommerz_image_preview"
                label_id="sslcommerz_image_label" input_id="sslcommerz_image_upload" :image="$payment_setting->sslcommerz_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="sslcommerz_test_mode" label="{{ __('Test Mode') }}" :checked="$payment_setting->sslcommerz_test_mode == '1'" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="sslcommerz_localhost" label="{{ __('Is Localhost') }}" :checked="$payment_setting->sslcommerz_localhost == '1'" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="sslcommerz_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$payment_setting->sslcommerz_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
