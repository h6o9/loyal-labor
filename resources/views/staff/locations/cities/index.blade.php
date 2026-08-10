@extends('admin.master_layout')
@section('title')
    <title>{{ __('City List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('City List') }}</h1>
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
                                <h4>{{ __('City List') }}</h4>
                                <div>
                                    @adminCan('city.create')
                                        <a class="btn btn-primary" href="{{ route('admin.city.create') }}"><i
                                                class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="citiesTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('State') }}</th>
                                                <th>{{ __('Country') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cities as $index => $city)
                                                <tr>
                                                    <td>{{ $cities->firstItem() + $index }}</td>
                                                    <td>{{ $city->name }}</td>
                                                    <td>{{ $city->state->name }}</td>
                                                    <td>{{ $city->state->country->name }}</td>
                                                    <td>
                                                        @adminCan('city.edit')
                                                            <a class="btn btn-primary btn-sm"
                                                                href="{{ route('admin.city.edit', $city->id) }}"><i
                                                                    class="fa fa-edit" aria-hidden="true"></i></a>
                                                        @endadminCan
                                                        @adminCan('city.delete')
                                                            <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal" href="javascript:;"
                                                                onclick="deleteData({{ $city->id }})"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                                        @endadminCan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                {{ $cities->links() }}
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
            let url = '{{ route('admin.city.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        $(document).ready(function() {

        })
    </script>
@endpush
