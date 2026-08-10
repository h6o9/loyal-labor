<div class="tab-pane fade" id="facebook_pixel_tab" role="tabpanel">
    <form action="{{ route('admin.update-facebook-pixel') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-input id="pixel_app_id"  name="pixel_app_id" label="{{ __('Facebook Pixel ID') }}" value="{{ $setting->pixel_app_id }}"/>
        </div>
        <div class="form-group">
            <x-admin.form-switch name="pixel_status" label="{{ __('Status') }}" active_value="active" inactive_value="inactive" :checked="$setting->pixel_status == 'active'"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>