<div class="tab-pane fade" id="crypto_tab" role="tabpanel">
    <form action="{{ route('admin.crypto-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <x-admin.form-select class="form-select" id="crypto_sandbox" name="crypto_sandbox"
                        label="{{ __('Account Mode') }}">
                        <x-admin.select-option value="0" :selected="$payment_setting->crypto_sandbox == '0'" text="{{ __('Live') }}" />
                        <x-admin.select-option value="1" :selected="$payment_setting->crypto_sandbox == '1'" text="{{ __('Sandbox') }}" />
                    </x-admin.form-select>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="crypto_receive_currency">{{ __('CoinGate Receiving Currency') }}
                    <span class="text-danger">*</span></label>
                @php
                    $rCurrencies = [
                        'BTC' => 'Bitcoin',
                        'EUR' => 'Euro',
                        'USD' => 'US Dollar',
                        'GBP' => 'British Pound',
                        'ETH' => 'Ethereum',
                    ];

                @endphp
                <select class="form-control select2" id="crypto_receive_currency" name="crypto_receive_currency">
                    @foreach ($rCurrencies as $currency => $name)
                        <option value="{{ $currency }}" @selected($currency == old('crypto_receive_currency', $payment_setting?->crypto_receive_currency))>
                            {{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <x-admin.form-input id="crypto_api_key" name="crypto_api_key"
                        value="{{ $payment_setting->crypto_api_key }}" label="{{ __('API Key') }}" required="true" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <x-admin.form-input id="crypto_charge" name="crypto_charge"
                        value="{{ $payment_setting->crypto_charge }}" label="{{ __('Gateway charge') }}(%)" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <x-admin.form-image-preview name="crypto_image" div_id="crypto_image_preview" label_id="crypto_image_label"
                input_id="crypto_image_upload" :image="$payment_setting->crypto_image" label="{{ __('Existing Image') }}"
                button_label="{{ __('Update Image') }}" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="crypto_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$payment_setting->crypto_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
