@extends('admin.master_layout')
@section('title')
    <title>{{ __('Country List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Country List') }}</h1>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form action="" method="GET" onchange="this.submit()">
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <div class="input-group">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <select class="form-select" id="order_by" name="order_by">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="asc" {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                    {{ __('ASC') }}
                                                </option>
                                                <option value="desc"
                                                    {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                    {{ __('DESC') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Country List') }}</h4>
                                <div>
                                    @adminCan('country.create')
                                        <a class="btn btn-primary" href="{{ route('admin.country.create') }}"><i
                                                class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($countries as $index => $country)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $country->name }}</td>
                                                    <td>
                                                        @adminCan('country.edit')
                                                            <a class="btn btn-primary btn-sm"
                                                                href="{{ route('admin.country.edit', $country->id) }}"><i
                                                                    class="fa fa-edit" aria-hidden="true"></i></a>
                                                        @endadminCan
                                                        @adminCan('country.delete')
                                                            <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal" href="javascript:;"
                                                                onclick="deleteData({{ $country->id }})"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                                        @endadminCan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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

        function deleteData(id) {
            let url = '{{ route('admin.country.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }
    </script>
@endpush
