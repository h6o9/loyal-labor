<div class="tab-pane fade" id="mmaintenance_mode_tab" role="tabpanel">
    <div class="form-group">
        <div class="alert alert-warning alert-has-icon">
            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
            <div class="alert-body">
                <div class="alert-title">{{ __('Warning') }}</div>
                {{ __("If you enable maintenance mode, regular users won't be able to access the website. Please make sure to inform users about the temporary unavailability.") }}
            </div>
        </div>
        <input onchange="changeMaintenanceModeStatus()" {{ $setting->maintenance_mode ? 'checked' : '' }}
            id="maintenance_mode_toggle" type="checkbox" data-toggle="toggle" data-onlabel="{{ __('Active') }}"
            data-offlabel="{{ __('Inactive') }}" data-onstyle="success" data-offstyle="danger">
    </div>
    <form action="{{ route('admin.update-maintenance-mode') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-image-preview div_id="maintenance_image_preview" label_id="maintenance_image_label"
                input_id="maintenance_image_upload" :image="$setting->maintenance_image" name="maintenance_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" :required="false" />
        </div>

        <div class="form-group">
            <x-admin.form-input id="maintenance_title" name="maintenance_title"
                label="{{ __('Maintenance Mode Title') }}" placeholder="{{ __('Enter Maintenance Mode Title') }}"
                value="{{ $setting->maintenance_title }}" required="true" />
        </div>
        <div class="form-group">
            <x-admin.form-editor id="maintenance_description" name="maintenance_description"
                label="{{ __('Maintenance Mode Description') }}" value="{!! $setting->maintenance_description !!}" required="true" />
        </div>
        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
