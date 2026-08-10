@extends('admin.master_layout')

@section('title')
    <title>{{ __('Admin Activity Logs') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Admin Activity Logs') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Admin Activity Logs') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Activity Logs') }}</h4>
                            </div>
                            <div class="card-body">
                                <!-- Filters -->
                                <form id="activityFilterForm" onsubmit="return false;" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="admin_id">{{ __('Select Admin') }}</label>
                                            <select name="admin_id" id="admin_id" class="form-control">
                                                <option value="">{{ __('All Sub Admins') }}</option>
                                                <option value="all" {{ request('admin_id') == 'all' ? 'selected' : '' }}>{{ __('All Admins (Including Super Admin)') }}</option>
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                                        {{ $admin->name }} ({{ $admin->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="action">{{ __('Action') }}</label>
                                            <select name="action" id="action" class="form-control">
                                                <option value="">{{ __('All Actions') }}</option>
                                                @foreach($actions as $action)
                                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                                        {{ ucfirst($action) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="start_date">{{ __('Start Date') }}</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                                   value="{{ request('start_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="end_date">{{ __('End Date') }}</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                                   value="{{ request('end_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label><br>
                                            <button type="button" id="activityFilterBtn" class="btn btn-primary">{{ __('Filter') }}</button>
                                            <button type="button" id="activityResetBtn" class="btn btn-secondary">{{ __('Reset') }}</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Activity Logs Table -->
                                <div class="table-responsive">
                                    <table class="table table-striped" id="activityLogsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('Admin') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('IP Address') }}</th>
                                                <th>{{ __('Date & Time') }}</th>
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

@push('css')
    <style>
        .badge-created { background-color: #28a745; }
        .badge-updated { background-color: #007bff; }
        .badge-deleted { background-color: #dc3545; }
        .badge-login { background-color: #17a2b8; }
        .badge-logout { background-color: #6c757d; }
        .badge-unknown { background-color: #ffc107; color: #212529; }
    </style>
@endpush

@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            var activityLogsTable = initServerDataTable('#activityLogsTable', {
                url: "{{ route('admin.activity-logs.index') }}",
                data: function(d) {
                    d.admin_id = $('#admin_id').val();
                    d.action = $('#action').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            }, [
                { data: 'id', name: 'id' },
                { data: 'admin_name', name: 'admin.name', orderable: false },
                { data: 'action_badge', name: 'action' },
                { data: 'description', name: 'description' },
                { data: 'ip_address', name: 'ip_address' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#activityFilterBtn').on('click', function() {
                activityLogsTable.ajax.reload();
            });

            $('#activityResetBtn').on('click', function() {
                $('#activityFilterForm')[0].reset();
                activityLogsTable.ajax.reload();
            });

            // Auto-refresh table data every 30 seconds without disrupting filters/pagination
            setInterval(function() {
                if (!document.hidden) {
                    activityLogsTable.ajax.reload(null, false);
                }
            }, 30000);
        });
    </script>
@endpush
