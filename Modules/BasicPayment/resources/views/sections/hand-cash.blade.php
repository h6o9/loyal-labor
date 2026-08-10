<div class="tab-pane fade" id="hand_cash_tab" role="tabpanel">
    <form action="{{ route('admin.update-cod-payment') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-input id="hand_cash_charge" name="hand_cash_charge"
                value="{{ $basic_payment->hand_cash_charge }}" label="{{ __('Gateway charge') }}(%)" />
        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="hand_cash_image" div_id="hand_image_preview" label_id="hand_image_label"
                input_id="hand_image_upload" :image="$basic_payment->hand_cash_image" label="{{ __('Existing Image') }}"
                button_label="{{ __('Update Image') }}" required="0" />
        </div>

        <div class="form-group">
            <x-admin.form-switch name="hand_cash_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$basic_payment->hand_cash_status == 'active'" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
