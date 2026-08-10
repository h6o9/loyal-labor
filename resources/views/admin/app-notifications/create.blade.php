@extends('admin.master_layout')



@section('title')

    <title>{{ __('Send App Notification') }}</title>

@endsection



@section('admin-content')

<div class="main-content">

    <section class="section">

        <div class="section-header d-flex justify-content-between">

            <h1>{{ __('Send App Notification') }}</h1>

            <a href="{{ route('admin.app-notifications.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>

        </div>



        <div class="section-body">

            <div class="card">

                <div class="card-body">

                    <form action="{{ route('admin.app-notifications.store') }}" method="POST">

                        @csrf

                        <div class="form-group">

                            <label>{{ __('Title') }} *</label>

                            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">

                        </div>

                        <div class="form-group">

                            <label>{{ __('Message') }} *</label>

                            <textarea name="body" class="form-control" rows="4" required>{{ old('body') }}</textarea>

                        </div>

                        <div class="form-group">

                            <label>{{ __('Target Audience') }} *</label>

                            <select name="target_audience" id="target_audience" class="form-control" required>

                                <option value="all" @selected(old('target_audience') === 'all')>{{ __('All Users') }}</option>

                                <option value="customers" @selected(old('target_audience') === 'customers')>{{ __('Customers Only') }}</option>

                                <option value="technicians" @selected(old('target_audience') === 'technicians')>{{ __('Technicians Only') }}</option>

                                <option value="specific_users" @selected(old('target_audience') === 'specific_users')>{{ __('Specific Users') }}</option>

                            </select>

                        </div>

                        <div class="form-group" id="specific_users_wrap" style="display: none;">

                            <label>{{ __('Search Users by Email') }} *</label>

                            <select name="user_ids[]" id="user_ids" class="form-control" multiple></select>

                            <small class="text-muted">{{ __('Type email or name. Selected users show as Customer or Technician.') }}</small>

                        </div>

                        <div class="form-group">

                            <label>{{ __('App Screen (optional)') }}</label>

                            <input type="text" name="screen" class="form-control" placeholder="bookings, profile, etc." value="{{ old('screen') }}">

                        </div>

                        <div class="form-group">

                            <label>{{ __('Reference ID (optional)') }}</label>

                            <input type="text" name="reference_id" class="form-control" value="{{ old('reference_id') }}">

                        </div>

                        <div class="form-check mb-3">

                            <input type="checkbox" name="send_now" value="1" class="form-check-input" id="send_now" checked>

                            <label class="form-check-label" for="send_now">{{ __('Send immediately via notification job') }}</label>

                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Save & Queue') }}</button>

                    </form>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection



@push('css')

<style>

    .select2-results__option .user-type-badge {

        float: right;

        font-size: 11px;

        padding: 2px 8px;

        border-radius: 10px;

        margin-left: 8px;

    }

    .select2-results__option .user-type-badge.customer {

        background: #e3f2fd;

        color: #1565c0;

    }

    .select2-results__option .user-type-badge.technician {

        background: #fff3e0;

        color: #ef6c00;

    }

    .select2-selection__choice .user-type-badge {

        margin-left: 6px;

        font-size: 10px;

        opacity: 0.85;

    }

</style>

@endpush



@push('js')

<script>

$(function () {

    const $audience = $('#target_audience');

    const $wrap = $('#specific_users_wrap');

    const $userSelect = $('#user_ids');

    const oldUserIds = @json(old('user_ids', []));



    function toggleSpecificUsers() {

        const isSpecific = $audience.val() === 'specific_users';

        $wrap.toggle(isSpecific);

        $userSelect.prop('required', isSpecific);

    }



    function formatUserResult(user) {

        if (!user.id) {

            return user.text;

        }



        const type = user.user_type || 'customer';

        const label = user.user_type_label || (type.charAt(0).toUpperCase() + type.slice(1));



        return $(

            '<span>' + user.text +

            ' <span class="user-type-badge ' + type + '">' + label + '</span></span>'

        );

    }



    function formatUserSelection(user) {

        if (!user.id) {

            return user.text;

        }



        const type = user.user_type || 'customer';

        const label = user.user_type_label || (type.charAt(0).toUpperCase() + type.slice(1));



        return user.text + ' [' + label + ']';

    }



    $userSelect.select2({

        width: '100%',

        placeholder: '{{ __('Search by email or name...') }}',

        minimumInputLength: 2,

        ajax: {

            url: '{{ route('admin.app-notifications.search-users') }}',

            dataType: 'json',

            delay: 250,

            data: function (params) {

                return { q: params.term };

            },

            processResults: function (data) {

                return { results: data.results || [] };

            }

        },

        templateResult: formatUserResult,

        templateSelection: formatUserSelection

    });



    $audience.on('change', toggleSpecificUsers);

    toggleSpecificUsers();



    if (oldUserIds.length) {

        $.ajax({

            url: '{{ route('admin.app-notifications.search-users') }}',

            data: { ids: oldUserIds }

        }).done(function (data) {

            (data.results || []).forEach(function (user) {

                const option = new Option(formatUserSelection(user), user.id, true, true);

                $(option).data('data', user);

                $userSelect.append(option);

            });

            $userSelect.trigger('change');

        });

    }

});

</script>

@endpush

