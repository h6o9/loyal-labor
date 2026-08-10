<div class="tab-pane fade" id="cookie_consent_tab" role="tabpanel">
    <form action="{{ route('admin.update-cookie-consent') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-select id="border" name="border" label="{{ __('Border') }}" class="form-select">
                        <x-admin.select-option :selected="$setting->border == 'none'" value="none" text="{{ __('None') }}" />
                        <x-admin.select-option :selected="$setting->border == 'thin'" value="thin" text="{{ __('Thin') }}" />
                        <x-admin.select-option :selected="$setting->border == 'normal'" value="normal" text="{{ __('Normal') }}" />
                        <x-admin.select-option :selected="$setting->border == 'thick'" value="thick" text="{{ __('Thick') }}" />
                    </x-admin.form-select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-select id="corners" name="corners" label="{{ __('Corner') }}" class="form-select">
                        <x-admin.select-option :selected="$setting->corners == 'none'" value="none" text="{{ __('None') }}" />
                        <x-admin.select-option :selected="$setting->corners == 'small'" value="small" text="{{ __('Small') }}" />
                        <x-admin.select-option :selected="$setting->corners == 'normal'" value="normal" text="{{ __('Normal') }}" />
                        <x-admin.select-option :selected="$setting->corners == 'large'" value="large" text="{{ __('Large') }}" />
                    </x-admin.form-select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-input type="color" id="bg_color" name="background_color"
                        label="{{ __('Background Color') }}" value="{{ $setting->background_color }}" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-input type="color" id="text_color" name="text_color" label="{{ __('Text Color') }}"
                        value="{{ $setting->text_color }}" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-input type="color" id="border_color" name="border_color"
                        label="{{ __('Border Color') }}" value="{{ $setting->border_color }}" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-input type="color" id="btn_bg_color" name="btn_bg_color"
                        label="{{ __('Button Color') }}" value="{{ $setting->btn_bg_color }}" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-input type="color" id="btn_text_color" name="btn_text_color"
                        label="{{ __('Button Text Color') }}" value="{{ $setting->btn_text_color }}" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-input id="link_text" name="link_text" label="{{ __('Link Text') }}"
                        placeholder="{{ __('Enter Link Text') }}" value="{{ $setting->link_text }}" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <x-admin.form-input id="btn_text" name="btn_text" label="{{ __('Button Text') }}"
                        placeholder="{{ __('Enter Button Text') }}" value="{{ $setting->btn_text }}" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <x-admin.form-textarea id="cookie_text" name="message" label="{{ __('Message') }}"
                placeholder="{{ __('Enter Message') }}" value="{{ $setting->message }}" maxlength="1000" />
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <x-admin.form-switch name="cookie_status" label="{{ __('Status') }}" active_value="active"
                    inactive_value="inactive" :checked="$setting->cookie_status == 'active'" />
            </div>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
