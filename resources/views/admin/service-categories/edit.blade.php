@extends('admin.master_layout')

@section('title')
    <title>{{ __('Edit Service Category') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Edit Service Category') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.service-categories.index') }}">{{ __('Service Categories') }}</a></div>
                <div class="breadcrumb-item">{{ __('Edit') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.service-categories.update', $serviceCategory->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $serviceCategory->name) }}" required>
                                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label>{{ __('Slug') }}</label>
                                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $serviceCategory->slug) }}">
                                        @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label>{{ __('Icon') }}</label>
                                        <input type="text" class="form-control" name="icon" value="{{ old('icon', $serviceCategory->icon) }}">
                                        @error('icon') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-3">
                                        <label>{{ __('Sort Order') }}</label>
                                        <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $serviceCategory->sort_order) }}" min="0">
                                    </div>

                                    <div class="form-group col-12 col-md-3">
                                        <label>{{ __('Status') }}</label>
                                        <select name="is_active" class="form-control">
                                            <option value="1" {{ old('is_active', $serviceCategory->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0" {{ old('is_active', $serviceCategory->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
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
