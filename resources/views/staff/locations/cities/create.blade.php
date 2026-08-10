@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create City') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create City') }}</h1>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.city.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('City') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.city.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="name" class="form-control" name="name">
                                        </div>
                                        <div class="form-group col-12">
                                            <label>{{ __('Country') }} <span class="text-danger">*</span></label>
                                            <select name="country_id" class="form-select select2">
                                                <option value="" selected disabled>{{ __('Select Country') }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ __($country->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>{{ __('State') }} <span class="text-danger">*</span></label>
                                            <select name="state_id" class="form-select">

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
    @include('components.admin.preloader')
@endsection
@push('js')
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
