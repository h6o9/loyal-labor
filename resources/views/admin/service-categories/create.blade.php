@extends('admin.master_layout')

@section('title')
    <title>{{ __('Create Service Category') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Create Service Category') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.service-categories.index') }}">{{ __('Service Categories') }}</a></div>
                <div class="breadcrumb-item">{{ __('Create') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.service-categories.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label>{{ __('Slug') }}</label>
                                        <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" placeholder="{{ __('Auto from name if empty') }}">
                                        @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label>{{ __('Icon') }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="icon_file" accept="image/*" required>
                                        <small class="text-muted">{{ __('Upload PNG/JPG/SVG icon. This icon is shown in the app.') }}</small>
                                        @error('icon_file') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-3">
                                        <label>{{ __('Sort Order') }}</label>
                                        <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    </div>

                                    <div class="form-group col-12 col-md-3">
                                        <label>{{ __('Status') }}</label>
                                        <select name="is_active" class="form-control">
                                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                        <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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
@endsection
