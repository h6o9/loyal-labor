@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Agent') }}</title>
@endsection
@section('admin-content')
@can('staff.create')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ __('Create Agent') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Create Agent') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control clear-error" placeholder="Enter Name">
                                                @error('name')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">{{ __('Email') }} <span class="text-danger">*</span></label>
                                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control clear-error" placeholder="Enter Email">
                                                @error('email')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror   
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control clear-error" placeholder="Enter Phone">
                                                @error('phone')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1" selected>{{ __('Active') }}</option>
                                                    <option value="0">{{ __('Inactive') }}</option>
                                                </select>
                                                @error('status')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="district_id">{{ __('District') }} <span class="text-danger">*</span></label>
                                                <select name="district_id" id="district_id" class="form-control select2" required>
                                                    <option value="">{{ __('Select District') }}</option>
                                                    @if(isset($districts))
                                                        @foreach($districts as $district)
                                                            <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                                                {{ $district->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('district_id')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <!-- <div class="form-group">
                                                <label>{{ __('Permissions') }}</label>
                                                <div class="row">
                                                    @foreach($permissions as $key => $name)
                                                        <div class="col-md-4 mb-2">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="perm_{{ $key }}" name="permissions[]" value="{{ $key }}">
                                                                <label class="custom-control-label" style="cursor: pointer;" for="perm_{{ $key }}">{{ __($name) }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                            <a href="{{ route('admin.staff.index') }}" class="btn btn-danger">{{ __('Cancel') }}</a>
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

<!-- Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for district dropdown
    $('.select2').select2({
        placeholder: "{{ __('Search district...') }}",
        allowClear: true,
        width: '100%'
    });
    
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