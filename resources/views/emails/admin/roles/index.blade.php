@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Roles') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Role') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Roles') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Manage Roles')" />
                                <div>
                                    @adminCan('role.create')
                                        <x-admin.add-button :href="route('admin.role.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-hover" id="rolesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Permission') }}</th>
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
    @adminCan('role.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            initServerDataTable('#rolesTable', "{{ route('admin.role.index') }}", [
                { data: 'name', name: 'name' },
                { data: 'permissions_count', name: 'permissions_count', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush