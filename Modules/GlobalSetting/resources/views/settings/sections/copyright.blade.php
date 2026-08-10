<div class="tab-pane fade" id="copyright_text_tab" role="tabpanel">
    <form action="{{ route('admin.update-copyright-text') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-textarea id="copyright_text" name="copyright_text" label="{{ __('Copyright Text') }}"
                placeholder="{{ __('Enter Copyright Text') }}" value="{{ $setting->copyright_text }}" maxlength="1000" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
