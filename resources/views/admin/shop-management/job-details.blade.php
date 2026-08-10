@extends('admin.master_layout')
@section('title')
    <title>Job Details</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Job Details</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.shop-management.index') }}">Shop Management</a></div>
                    <div class="breadcrumb-item">Job Details</div>
                </div>
            </div>

            <div class="section-body">
                @if($job)
                    <!-- Shop Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4><i class="fas fa-store"></i> Shop Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Shop Name:</strong> {{ $job->shop->shop_name ?? 'N/A' }}</p>
                                            <p><strong>Owner Name:</strong> {{ $job->shop->owner_name ?? 'N/A' }}</p>
                                            <p><strong>Category:</strong> 
                                                <span class="badge badge-{{ $job->shop->category == 'electrician' ? 'primary' : ($job->shop->category == 'wifi_controller' ? 'info' : ($job->shop->category == 'solar' ? 'warning' : 'success')) }}">
                                                    {{ $job->shop->category_label ?? $job->shop->category ?? 'N/A' }}
                                                </span>
                                            </p>
                                            <p><strong>Phone:</strong> {{ $job->shop->phone_number ?? $job->shop->phone ?? 'N/A' }}</p>
                                            <p><strong>WhatsApp:</strong> {{ $job->shop->whatsapp_number ?? 'N/A' }}</p>
                                            <p><strong>Address:</strong> {{ $job->shop->address ?? 'N/A' }}</p>
                                            @if($job->shop->about_shop)
                                                <p><strong>About Shop:</strong><br>{{ $job->shop->about_shop }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4><i class="fas fa-tasks"></i> Job Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                         <p><strong>Status:</strong> 
    <span class="badge badge-{{ $job->status == 'pending' ? 'warning' : ($job->status == 'in_progress' ? 'info' : 'success') }}">
         @if($job->status == 'pending')
                                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                        @else
                                                            <span class="badge badge-success">{{ __('Done') }}</span>
                                                        @endif
    </span>
</p>
                                            <p><strong>Assigned To:</strong> {{ $job->assignedTo->name ?? 'N/A' }}</p>
                                            <p><strong>Staff Email:</strong> {{ $job->assignedTo->email ?? 'N/A' }}</p>
                                            <p><strong>Assigned By:</strong> {{ $job->assignedBy->name ?? 'N/A' }}</p>
                                            <p><strong>Created At:</strong> {{ $job->created_at->format('d M Y, h:i A') }}</p>
                                            <p><strong>Updated At:</strong> {{ $job->updated_at->format('d M Y, h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    @if($job->scheduled_date)
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4><i class="fas fa-calendar"></i> Schedule Information</h4>
                                </div>
                                <div class="card-body">
                                    <p><strong>Scheduled Date:</strong> {{ \Carbon\Carbon::parse($job->scheduled_date)->format('d M Y') }}</p>
                                    @if($job->scheduled_time)
                                        <p><strong>Scheduled Time:</strong> {{ \Carbon\Carbon::parse($job->scheduled_time)->format('h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Job Description -->
                    @if($job->description)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4><i class="fas fa-file-alt"></i> Job Description</h4>
                                </div>
                                <div class="card-body">
                                    <p>{{ $job->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Additional Notes -->
                    @if($job->notes)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4><i class="fas fa-sticky-note"></i> Additional Notes</h4>
                                </div>
                                <div class="card-body">
                                    <p>{{ $job->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ route('admin.shop-management.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to List
                                    </a>
                                    

                                    
                                    @if($job->status == 'in_progress')
                                        <button class="btn btn-success" onclick="updateJobStatus({{ $job->id }}, 'completed')">
                                            <i class="fas fa-check"></i> Complete Job
                                        </button>
                                    @endif
                                    
                                    @if(auth('admin')->user()->hasPermissionTo('assign.job.delete'))
                                        <x-admin.delete-button :id="$job->id" onclick="deleteData" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Job not found!
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Delete Modal -->
    @if(auth('admin')->user()->hasPermissionTo('assign.job.delete'))
        <x-admin.delete-modal />
    @endif
@endsection

@push('js')
    <script>
        function updateJobStatus(jobId, status) {
            if(confirm('Are you sure you want to update the job status to ' + status + '?')) {
                $.ajax({
                    url: '{{ route("admin.shop-management.toggle-job-status", ":id") }}'.replace(':id', jobId),
                    type: 'POST',
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

        // Delete function for assigned jobs
        @if(auth('admin')->user()->hasPermissionTo('assign.job.delete'))
        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ route('admin.assigned-jobs.destroy', ':id') }}".replace(':id', id));
        }
        @endif
    </script>
@endpush
