@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create District') }}</title>
@endsection

@section('admin-content')
@can('district.create')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('admin.districts.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ __('Create District') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Create District') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.districts.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ __('District Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control clear-error" placeholder="{{ __('Enter District Name') }}">
                                                @error('name')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="active" selected>{{ __('Active') }}</option>
                                                    <option value="inactive">{{ __('Inactive') }}</option>
                                                </select>
                                                @error('status')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">{{ __('Save District') }}</button>
                                            <a href="{{ route('admin.districts.index') }}" class="btn btn-danger">{{ __('Cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script>
    $(document).ready(function() {
        // Input field mein click ya type karne par error hide ho jaye
        $('.clear-error').on('click keyup focus', function() {
            $(this).closest('.form-group').find('.error-message').fadeOut('fast');
        });
        
        // Agar error message par click karo to bhi hide ho
        $('.error-message').on('click', function() {
            $(this).fadeOut('fast');
        });
    });
    </script>
@endsection
@endcan