@extends('admin.master_layout')
@section('title')
    <title>{{ __('Update City') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Update City') }}</h1>
            </div>

            <div class="section-body">
                <a class="btn btn-primary" href="{{ route('admin.city.index') }}"><i class="fas fa-list"></i>
                    {{ __('City') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.city.update', $city->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                            <input class="form-control" id="name" name="name" type="text"
                                                value="{{ $city->name }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Country') }} <span class="text-danger">*</span></label>
                                            <select class="form-control select2" name="country_id">
                                                <option value="" selected disabled>{{ __('Select Country') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        @if ($cityCountry->id == $country->id) selected @endif>
                                                        {{ __($country->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>{{ __('State') }} <span class="text-danger">*</span></label>
                                            <select class="form-select" name="state_id">
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        @if ($city->state_id == $state->id) selected @endif>
                                                        {{ __($state->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
        "use strict";
        $(document).ready(function() {
            $('[name="state_id"]').select2();
        })
    </script>

    <script>
        "use strict";
        $(document).ready(function() {
            $('[name="state_id"]').select2();

            $('[name="country_id"]').on('change', function() {
                $('.preloader_area').removeClass('d-none');
                var country_id = $(this).val();
                $.ajax({
                    url: "{{ route('admin.get.all.states.by.country', '') }}/" + country_id,
                    success: function(response) {
                        $('[name="state_id"]').html('');
                        $('[name="state_id"]').append(
                            '<option value="" selected disabled>{{ __('Select State') }}</option>'
                        );
                        response.data.forEach(function(state) {
                            $('[name="state_id"]').append('<option value="' + state.id +
                                '">' + state.name + '</option>');
                        })
                        $('.preloader_area').addClass('d-none');
                    }
                })
            })
        })
    </script>
@endpush
