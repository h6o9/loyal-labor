@extends('admin.master_layout')
@section('title')
    <title>Staff Permissions</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Staff Permissions</h1>
            </div>

            <div class="section-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Staff Members & Permissions</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="staffTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Staff Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Permissions') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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
            initServerDataTable('#staffTable', "{{ route('admin.staff-permissions.index') }}", [
                { data: 'name_badge', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'permissions_link', name: 'permissions_link', orderable: false, searchable: false },
                { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
