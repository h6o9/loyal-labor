@extends('admin.master_layout')
@section('title')
    <title>Shop Details - {{ $shop->shop_name }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Shop Details</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.shop-management.index') }}">Shop Management</a></div>
                    <div class="breadcrumb-item">{{ $shop->shop_name }}</div>
                </div>
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

                <!-- Shop Information -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Shop Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Shop Name:</strong> {{ $shop->shop_name }}</p>
                                        <p><strong>Owner Name:</strong> {{ $shop->owner_name }}</p>
                                        <p><strong>Category:</strong> <span class="badge badge-{{ $shop->category == 'electrician' ? 'primary' : ($shop->category == 'wifi_controller' ? 'info' : ($shop->category == 'solar' ? 'warning' : 'success')) }}">{{ $shop->category_label }}</span></p>
                                        <p><strong>Phone:</strong> {{ $shop->phone_number }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>WhatsApp:</strong> {{ $shop->whatsapp_number }}</p>
                                        <p><strong>Address:</strong> {{ $shop->address }}</p>
                                        <p><strong>Created By:</strong> 
                                            @if($shop->staff)
                                                <span class="badge badge-info">{{ $shop->staff->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </p>
                                        <p><strong>Created At:</strong> {{ $shop->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>
                                @if($shop->about_shop)
                                    <div class="mt-3">
                                        <p><strong>About Shop:</strong></p>
                                        <p>{{ $shop->about_shop }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Quick Actions</h4>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-primary btn-block mb-2" onclick="openAssignModal()">
                                    <i class="fa fa-user-plus"></i> Assign Job
                                </button>
                                <a href="{{ route('admin.shop-management.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Jobs -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>Assigned Jobs</h4>
                                <button class="btn btn-primary btn-sm" onclick="openAssignModal()">
                                    <i class="fa fa-plus"></i> Assign New Job
                                </button>
                            </div>
                            <div class="card-body">
                                @if($shop->jobs->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Job Type</th>
                                                    <th>Assigned To</th>
                                                    <th>Assigned By</th>
                                                    <th>Status</th>
                                                    <th>Scheduled</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($shop->jobs as $job)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-info">{{ $job->job_type }}</span>
                                                        </td>
                                                        <td>
                                                            @if($job->assignedTo)
                                                                {{ $job->assignedTo->name }}
                                                                <br>
                                                                <small class="text-muted">{{ $job->assignedTo->email }}</small>
                                                            @else
                                                                <span class="text-muted">Unassigned</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($job->assignedBy)
                                                                {{ $job->assignedBy->name }}
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-{{ $job->status == 'pending' ? 'warning' : ($job->status == 'in_progress' ? 'info' : ($job->status == 'completed' ? 'success' : 'danger')) }}">
                                                                {{ ucfirst($job->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($job->scheduled_date)
                                                                {{ $job->scheduled_date->format('d M Y') }}
                                                                @if($job->scheduled_time)
                                                                    {{ $job->scheduled_time->format('h:i A') }}
                                                                @endif
                                                            @else
                                                                <span class="text-muted">Not scheduled</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm btn-info" onclick="viewJobDetails({{ $job->id }})">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                                @if($job->status == 'pending')
                                                                    <button class="btn btn-sm btn-warning" onclick="updateJobStatus({{ $job->id }}, 'in_progress')">
                                                                        <i class="fa fa-play"></i>
                                                                    </button>
                                                                @endif
                                                                @if($job->status == 'in_progress')
                                                                    <button class="btn btn-sm btn-success" onclick="updateJobStatus({{ $job->id }}, 'completed')">
                                                                        <i class="fa fa-check"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fa fa-tasks fa-3x text-muted mb-3"></i>
                                        <h5>No Jobs Assigned</h5>
                                        <p class="text-muted">Click "Assign New Job" to assign tasks to staff members.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Assign Job Modal -->
    <div class="modal fade" id="assignJobModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Jobs - {{ $shop->shop_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignJobForm" method="POST" action="{{ route('admin.shop-management.assign', $shop->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Staff Member <span class="text-danger">*</span></label>
                            <select name="assigned_to" id="assignedTo" class="form-control" required>
                                <option value="">Select Staff Member</option>
                                @foreach($allStaff as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Job description..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Scheduled Date</label>
                                    <input type="date" name="scheduled_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Scheduled Time</label>
                                    <input type="time" name="scheduled_time" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Jobs</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function openAssignModal() {
            $('#assignJobModal').modal('show');
        }

        function viewJobDetails(jobId) {
            // You can implement a modal to show job details
            alert('Job details functionality can be implemented here');
        }

        function updateJobStatus(jobId, status) {
            if(confirm('Are you sure you want to update the job status to ' + status + '?')) {
                // Implement AJAX call to update job status
                $.ajax({
                    url: '/admin/shop-management/job/' + jobId + '/status',
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Error updating job status');
                    }
                });
            }
        }
    </script>
@endpush
