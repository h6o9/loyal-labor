<div class="tab-pane fade" id="logo_favicon_tab" role="tabpanel">
    <form action="{{ route('admin.update-logo-favicon') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-image-preview name="logo" :image="$setting->logo" label="{{ __('Existing Logo') }}"
                button_label="{{ __('Update Image') }}" :required="false" />
        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="logo_dark" div_id="logo-dark-preview" label_id="logo-dark-label"
                input_id="logo-dark-upload" :image="$setting->logo_dark" label="{{ __('Existing Dark Logo') }}"
                button_label="{{ __('Update Image') }}" :required="false" />
        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="favicon" div_id="favicon-preview" label_id="favicon-label"
                input_id="favicon-upload" :image="$setting->favicon" label="{{ __('Existing Favicon') }}"
                button_label="{{ __('Update Image') }}" :required="false" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
