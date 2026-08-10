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
                                    <table class="table table-striped" id="countriesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
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
        </section>
    </div>
@endsection
@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#countriesTable', "{{ route('admin.country.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
