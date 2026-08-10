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
                                    <table class="table table-striped" id="citiesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('State') }}</th>
                                                <th>{{ __('Country') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#citiesTable', "{{ route('admin.city.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'state_name', name: 'state.name', orderable: false },
                { data: 'country_name', name: 'state.country.name', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
