<div class="tab-pane fade active show" id="stripe_payment_tab" role="tabpanel">
    <form action="{{ route('admin.update-stripe') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-input id="stripe_charge" name="stripe_charge" label="{{ __('Gateway charge') }}(%)"
                    value="{{ $basic_payment->stripe_charge }}" />
            </div>
            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="stripe_key" name="stripe_key" label="{{ __('Stripe Key') }}"
                        value="STRIPE-SITE-KEY-TEST" required="true" />
                @else
                    <x-admin.form-input id="stripe_key" name="stripe_key" label="{{ __('Stripe Key') }}"
                        value="{{ $basic_payment->stripe_key }}" required="true" />
                @endif
            </div>

            <div class="form-group col-md-12">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="stripe_secret" name="stripe_secret" label="{{ __('Stripe Secret') }}"
                        value="STRIPE-TEST98384934-SECRET-KEY" required="true" />
                @else
                    <x-admin.form-input id="stripe_secret" name="stripe_secret" label="{{ __('Stripe Secret') }}"
                        value="{{ $basic_payment->stripe_secret }}" required="true" />
                @endif
            </div>
        </div>
        <div class="form-group">
            <x-admin.form-image-preview div_id="stripe_image_preview" label_id="stripe_image_label"
                input_id="stripe_image_upload" :image="$basic_payment->stripe_image" name="stripe_image" label="{{ __('Existing Image') }}"
                button_label="{{ __('Update Image') }}" required="0" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="stripe_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$basic_payment->stripe_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>