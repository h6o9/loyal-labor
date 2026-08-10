@extends('admin.master_layout')
@section('title')
<title>Complaints / Help & Support</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Complaints</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Complaints</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>User</th>
                                            <th>Issue Category</th>
                                            <th>Priority</th>
                                            <th>Date</th>
                                            <th>Action</th>
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
            initServerDataTable('#table-1', "{{ route('admin.help-supports.index') }}", [
                { data: 'user_name', name: 'user.name', orderable: false },
                { data: 'issue_category', name: 'issue_category' },
                { data: 'priority_badge', name: 'priority' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
