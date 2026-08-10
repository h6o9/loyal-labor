<div class="tab-pane fade" id="direct_bank_tab" role="tabpanel">
    <form action="{{ route('admin.update-bank-payment') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-input id="bank_charge" name="bank_charge" label="{{ __('Gateway charge') }}(%)"
                value="{{ $basic_payment->bank_charge }}" />
        </div>
        <div class="form-group">
            @if (env('APP_MODE') == 'DEMO')
            <x-admin.form-textarea id="bank_information" name="bank_information" label="{{ __('Account Information') }}"
            value="BANK-TEST-INFORMATION" required="true"/>
            @else
            <x-admin.form-textarea id="bank_information" name="bank_information" label="{{ __('Account Information') }}"
            value="{{ $basic_payment->bank_information }}" required="true"/>
            @endif
        </div>

        <div class="form-group">
            <x-admin.form-image-preview div_id="bank_image_preview" label_id="bank_image_label"
                input_id="bank_image_upload" :image="$basic_payment->bank_image" name="bank_image" label="{{ __('Existing Image') }}"
                button_label="{{ __('Update Image') }}" required="0"/>
        </div>
        <div class="form-group">
            <x-admin.form-switch name="bank_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$basic_payment->bank_status == 'active'" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>