@extends('admin.master_layout')
@section('title')
    <title>Bookings</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Bookings Management</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="bookingsTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Reference Code</th>
                                            <th>Customer</th>
                                            <th>Technician</th>
                                            <th>Status</th>
                                            <th>Date/Time</th>
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
            initServerDataTable('#bookingsTable', "{{ route('admin.bookings.index') }}", [
                { data: 'reference', name: 'booking_reference' },
                { data: 'customer_name', name: 'customer.name', orderable: false },
                { data: 'technician_name', name: 'technician.name', orderable: false },
                { data: 'status_badge', name: 'status' },
                { data: 'date_time', name: 'service_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush
