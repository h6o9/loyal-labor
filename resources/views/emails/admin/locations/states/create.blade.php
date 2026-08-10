@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create State') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create State') }}</h1>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.state.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('State') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.state.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="name" class="form-control" name="name">
                                        </div>
                                        <div class="form-group col-12">
                                            <label>{{ __('Country') }} <span class="text-danger">*</span></label>
                                            <select name="country_id" class="form-select">
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ __($country->name) }}</option>
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
            $('[name="country_id"]').select2({});
        })
    </script>
@endpush
