@extends('staff.master_layout')
@section('title')
    <title>{{ __('My Jobs') }}</title>
@endsection
@section('staff-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('My Jobs') }}" :list="[]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('My Assigned Jobs')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="jobsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Assigned By') }}</th>
                                                <th>{{ __('Scheduled Date') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Additional Notes') }}</th>
                                                <th>{{ __('From') }}</th>
                                                <th>{{ __('To') }}</th>
                                                <th>{{ __('Navigation') }}</th>
                                                <th>{{ __('Status') }}</th>
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

    <!-- Single Modal for Description (Dynamic Content) -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">
                        <i class="fas fa-align-left"></i> {{ __('Job Description') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="descriptionModalBody">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Modal for Notes (Dynamic Content) -->
    <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notesModalLabel">
                        <i class="fas fa-sticky-note"></i> {{ __('Additional Notes') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="notesModalBody">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Done/Undo -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalTitle">{{ __('Confirm Action') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="confirmationModalBody">
                    {{ __('Are you sure?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">{{ __('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let pendingJobId = null;
        let pendingAction = null;
        let pendingButton = null;

        $(document).ready(function() {
            initServerDataTable('#jobsTable', "{{ route('staff.jobs.index') }}", [
                { data: 'shop_name', name: 'shop_name', orderable: false, searchable: false },
                { data: 'assigned_by_name', name: 'assigned_by_name', orderable: false, searchable: false },
                { data: 'scheduled_date', name: 'scheduled_date' },
                { data: 'description_btn', name: 'description_btn', orderable: false, searchable: false },
                { data: 'notes_btn', name: 'notes_btn', orderable: false, searchable: false },
                { data: 'from_location', name: 'from_location', orderable: false, searchable: false },
                { data: 'to_location', name: 'to_location', orderable: false, searchable: false },
                { data: 'navigation', name: 'navigation', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            // Handle View Description Button Click
            $(document).on('click', '.view-description-btn', function() {
                var description = $(this).data('description');
                
                var modalContent = `
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="mb-3">
                                <div class="mt-2 p-3 bg-light rounded" style="white-space: pre-wrap; word-wrap: break-word;">
                                    <p class="mb-0">${description}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#descriptionModalBody').html(modalContent);
                $('#descriptionModal').modal('show');
            });
            
            // Handle View Notes Button Click
            $(document).on('click', '.view-notes-btn', function() {
                var notes = $(this).data('notes');
                
                var modalContent = `
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="mb-3">
                                <div class="mt-2 p-3 bg-light rounded" style="white-space: pre-wrap; word-wrap: break-word;">
                                    <p class="mb-0">${notes}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#notesModalBody').html(modalContent);
                $('#notesModal').modal('show');
            });

            // Handle Mark as Done button click
            $(document).on('click', '.mark-done-btn', function() {
                var jobId = $(this).data('id');
                var shopName = $(this).data('shop');
                
                pendingJobId = jobId;
                pendingAction = 'done';
                pendingButton = $(this);
                
                $('#confirmationModalTitle').html('{{ __("Mark as Done") }}');
                $('#confirmationModalBody').html(`{{ __("Are you sure you want to mark this job") }} {{ __("as done?") }}`);
                $('#confirmActionBtn').removeClass('btn-warning').addClass('btn-success');
                $('#confirmActionBtn').html('{{ __("Yes, Mark as Done") }}');
                $('#confirmationModal').modal('show');
            });

            // Handle Mark as Undone button click
            $(document).on('click', '.mark-undone-btn', function() {
                var jobId = $(this).data('id');
                var shopName = $(this).data('shop');
                
                pendingJobId = jobId;
                pendingAction = 'undone';
                pendingButton = $(this);
                
                $('#confirmationModalTitle').html('{{ __("Mark as Pending") }}');
                $('#confirmationModalBody').html(`{{ __("Are you sure you want to mark job for shop") }} "${shopName}" {{ __("as pending?") }}`);
                $('#confirmActionBtn').removeClass('btn-success').addClass('btn-warning');
                $('#confirmActionBtn').html('{{ __("Yes, Mark as Pending") }}');
                $('#confirmationModal').modal('show');
            });

            // Confirm action
            $('#confirmActionBtn').on('click', function() {
                if (!pendingJobId || !pendingAction) return;
                
                // Disable button and show loading
                pendingButton.prop('disabled', true);
                pendingButton.html('<i class="fas fa-spinner fa-spin"></i> {{ __("Processing...") }}');
                
                // Close confirmation modal
                $('#confirmationModal').modal('hide');
                
                let url = '';
                if (pendingAction === 'done') {
                    url = '{{ route("staff.jobs.mark-done", ":id") }}'.replace(':id', pendingJobId);
                } else {
                    url = '{{ route("staff.jobs.mark-undone", ":id") }}'.replace(':id', pendingJobId);
                }
                
                $.ajax({
                    url: url,
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
                        pendingButton.prop('disabled', false);
                        if (pendingAction === 'done') {
                            pendingButton.html('<i class="fas fa-check"></i> {{ __("Done") }}');
                        } else {
                            pendingButton.html('<i class="fas fa-undo"></i> {{ __("Undo") }}');
                        }
                        
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('{{ __("Something went wrong. Please try again.") }}');
                        }
                    }
                });
                
                // Reset pending variables
                pendingJobId = null;
                pendingAction = null;
                pendingButton = null;
            });
        });
    </script>
@endpush