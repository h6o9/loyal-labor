<div class="tab-pane fade" id="default_avatar_tab" role="tabpanel">
    <form action="{{ route('admin.update-default-avatar') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-image-preview name="default_avatar" div_id="default_avatar_preview"
                label_id="default_avatar_label" input_id="default_avatar_upload" :image="$setting->default_avatar"
                label="{{ __('No Image') }}" button_label="{{ __('Update Image') }}" />
        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="default_user_image" div_id="default_user_image_preview"
                label_id="default_user_image_label" input_id="default_user_image_upload" :image="$setting->default_user_image"
                label="{{ __('Existing Avater') }}" button_label="{{ __('Update Image') }}" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
