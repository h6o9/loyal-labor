<div class="tab-pane fade" id="breadcrump_img_tab" role="tabpanel">
    <form action="{{ route('admin.update-breadcrumb') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-image-preview div_id="breadcrumb_image_preview" label_id="breadcrumb_image_label" input_id="breadcrumb_image_upload" :image="$setting->breadcrumb_image" name="breadcrumb_image" label="{{ __('Existing Breadcrumb Image') }}" button_label="{{ __('Update Image') }}" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
