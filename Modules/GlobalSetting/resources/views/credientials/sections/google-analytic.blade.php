<div class="tab-pane fade" id="google_analytic_tab" role="tabpanel">
    <form action="{{ route('admin.update-google-analytic') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-input id="google_analytic_id"  name="google_analytic_id" label="{{ __('Measurement ID') }}" value="{{ $setting->google_analytic_id }}"/>
        </div>
        <div class="form-group">
            <x-admin.form-switch name="google_analytic_status" label="{{ __('Status') }}" active_value="active" inactive_value="inactive" :checked="$setting->google_analytic_status == 'active'"/>
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>