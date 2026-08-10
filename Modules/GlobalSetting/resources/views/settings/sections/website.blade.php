<div class="tab-pane fade" id="website_tab" role="tabpanel">
    <form action="{{ route('admin.update-general-setting') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-select id="timezone" name="timezone"
                label="{{ __('Timezone') }} ({{ __('Current') }}: {{ config('app.timezone') }})" class="select2">
                @foreach ($all_timezones as $timezone)
                    <x-admin.select-option :selected="$setting->timezone == $timezone->name" value="{{ $timezone->name }}"
                        text="{{ $timezone->name }}" />
                @endforeach
            </x-admin.form-select>
        </div>

        <div class="form-group">
            <x-admin.form-select id="time_format" name="time_format"
                label="{{ __('Time Format') }} ({{ __('Current') }}:
                {{ formattedTime(carbonNowWithTimeZone()) }})"
                class="select2">
                @foreach ($all_time_format as $time_format => $time_format_text)
                    <x-admin.select-option :selected="$setting->time_format == $time_format" value="{{ $time_format }}"
                        text="{{ $time_format_text }}" />
                @endforeach
            </x-admin.form-select>
        </div>

        <div class="form-group">
            <x-admin.form-select id="date_format" name="date_format"
                label="{{ __('Date Format') }} ({{ __('Current') }}:
                {{ formattedDate(carbonNowWithTimeZone()) }})"
                class="select2">
                @foreach ($all_date_format as $date_format => $date_format_text)
                    <x-admin.select-option :selected="$setting->date_format == $date_format" value="{{ $date_format }}"
                        text="{{ $date_format_text }}" />
                @endforeach
            </x-admin.form-select>
        </div>
        <div class="form-group">
            <label for="">{{ __('Date Time Example') }}:
                {{ formattedDateTime(carbonNowWithTimeZone()) }}</label>
        </div>

        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
