<div class="col-md-12">
    <div class="wsus__checkout_form_input">
        <label>{{ __('Name') }}</label>
        <input name="name" type="text" value="{{ old('name', auth()->user()?->name) }}"
            placeholder="{{ __('Name') }}">
    </div>
</div>
<div class="col-md-6 col-xl-6">
    <div class="wsus__checkout_form_input">
        <label>{{ __('Email Address') }}</label>
        <input name="email" type="email" value="{{ old('email', auth()->user()?->email) }}"
            placeholder="{{ __('Email') }}">
    </div>
</div>
<div class="col-md-6 col-xl-6">
    <div class="wsus__checkout_form_input">
        <label>{{ __('Phone') }}*</label>
        <input name="phone" type="text" value="{{ old('phone', auth()->user()?->phone) }}"
            placeholder="{{ __('Phone') }}">
    </div>
</div>
<div class="col-12">
    <div class="wsus__checkout_form_input">
        <div class="form-check form-check-inline">
            <input name="same_as_shipping" type="hidden" value="0">
            <label class="form-check-label" for="same_as_shipping">
                <input class="form-check-input" id="same_as_shipping" name="same_as_shipping" type="checkbox"
                    value="1" checked>
                <span class="same_as_shipping_label">
                    {{ __('Is same as shipping address') }}
                </span>
            </label>
        </div>
    </div>
</div>
<div class="d-none" id="billing_address_fields">
    <div class="row">
        <div class="col-md-6 col-xl-6">
            <div class="wsus__checkout_form_input">
                <label for="shipping_country">{{ __('Country') }}<span class="text-danger">*</span></label>
                <select class="select_2" id="shipping_country" name="country_id">
                    <option value="">{{ __('Select Country') }}</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="wsus__checkout_form_input">
                <label for="shipping_state">{{ __('State') }}<span class="text-danger">*</span></label>
                <select class="select_2" id="shipping_state" name="state_id">
                    <option value="">{{ __('Select State') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="wsus__checkout_form_input">
                <label for="shipping_city">{{ __('City') }}<span class="text-danger">*</span></label>
                <select class="select_2" id="shipping_city" name="city_id">
                    <option value="">{{ __('Select City') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 col-xl-6">
            <div class="wsus__checkout_form_input">
                <label>{{ __('Zip / Postal Code') }}</label>
                <input name="zip" type="text" value="{{ old('zip') }}" placeholder="{{ __('Zip Code') }}">
            </div>
        </div>
        <div class="col-xl-12">
            <div class="wsus__checkout_form_input">
                <label>{{ __('Street address') }}</label>
                <input name="address" type="text" placeholder="{{ __('House number and street name') }}">
            </div>
        </div>
    </div>
</div>
