@extends('staff.master_layout')
@section('title')
    <title>{{ __('Job Details') }}</title>
@endsection
@section('staff-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Job Details') }}" :list="[
                ['text' => __('My Jobs'), 'href' => route('staff.jobs.index')],
                ['text' => __('Job Details')]
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Job Details') }}" />
                                <div>
                                    <a href="{{ route('staff.jobs.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>{{ __('Shop Information') }}</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>{{ __('Shop Name') }}:</strong></td>
                                                <td>{{ $job->shop->shop_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Owner Name') }}:</strong></td>
                                                <td>{{ $job->shop->owner_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Phone') }}:</strong></td>
                                                <td>{{ $job->shop->phone_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Address') }}:</strong></td>
                                                <td>{{ $job->shop->address ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>{{ __('Job Information') }}</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>{{ __('Assigned By') }}:</strong></td>
                                                <td>{{ $job->assignedBy->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Status') }}:</strong></td>
                                                <td>
                                                    @if($job->status == 'pending')
                                                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                    @else
                                                        <span class="badge badge-success">{{ __('Done') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Created At') }}:</strong></td>
                                                <td>{{ $job->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                @if($job->scheduled_date)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6>{{ __('Schedule') }}</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>{{ __('Date') }}:</strong></td>
                                                <td>{{ $job->scheduled_date }}</td>
                                            </tr>
                                            @if($job->scheduled_time)
                                            <tr>
                                                <td><strong>{{ __('Time') }}:</strong></td>
                                                <td>{{ $job->scheduled_time }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6>{{ __('Description') }}</h6>
                                        <p>{{ $job->description ?: 'No description provided' }}</p>
                                    </div>
                                </div>
                                
                                @if($job->notes)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6>{{ __('Additional Notes') }}</h6>
                                        <p>{{ $job->notes }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        @if($job->status == 'pending')
                                            <button type="button" class="btn btn-success" onclick="markAsDone({{ $job->id }})">
                                                <i class="fas fa-check"></i> {{ __('Mark as Done') }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-warning" onclick="markAsUndone({{ $job->id }})">
                                                <i class="fas fa-undo"></i> {{ __('Mark as Pending') }}
                                            </button>
                                        @endif
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
    <script>
        function markAsDone(jobId) {
            if(!confirm('Are you sure you want to mark this job as done?')) {
                return;
            }
            
            $.ajax({
                url: '{{ route("staff.jobs.mark-done", ":id") }}'.replace(':id', jobId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        }

        function markAsUndone(jobId) {
            if(!confirm('Are you sure you want to mark this job as pending?')) {
                return;
            }
            
            $.ajax({
                url: '{{ route("staff.jobs.mark-undone", ":id") }}'.replace(':id', jobId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        }
    </script>
@endpush
