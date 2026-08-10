@extends('admin.master_layout')
@section('title')
    <title>{{ __('Assigned Jobs') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Assigned Jobs') }}" :list="[
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Assigned Jobs')" />
                                <div>
                                    @if(auth('admin')->user()->hasPermissionTo('shop.view'))
                                    <a href="{{ route('admin.shop-management.shop-list') }}" class="btn btn-primary">
                                        <i class="fas fa-store"></i> {{ __('Shop List') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="assignedJobsTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Scheduled Date & Time') }}</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Shop Address') }}</th>
                                                <th>{{ __('Assigned To') }}</th>
                                                <th>{{ __('Assigned By') }}</th>
                                                <th>{{ __('From (Staff)') }}</th>
                                                <th>{{ __('To (Shop)') }}</th>
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

    <!-- Description Modal -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Job Description') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="descriptionContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Modal -->
    <div class="modal fade" id="notesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Additional Notes') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="notesContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @if(auth('admin')->user()->hasPermissionTo('assign.job.delete'))
        <x-admin.delete-modal />
    @endif

    <!-- Delete Modal -->
    @can('assign.job.delete')
        <x-admin.delete-modal />
    @endcan
@endsection

@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#assignedJobsTable', "{{ route('admin.shop-management.index') }}", [
                { data: 'scheduled_datetime', name: 'scheduled_date' },
                { data: 'shop_name', name: 'shop.shop_name', orderable: false },
                { data: 'shop_address', name: 'shop.address', orderable: false },
                { data: 'assigned_to_name', name: 'assignedTo.name', orderable: false },
                { data: 'assigned_by_name', name: 'assignedBy.name', orderable: false },
                { data: 'from_staff', name: 'from_staff', orderable: false, searchable: false },
                { data: 'to_shop', name: 'to_shop', orderable: false, searchable: false },
                { data: 'navigation', name: 'navigation', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });
    </script>
@endpush

@push('js')
    <script>
        function viewDescription(jobId, description) {
            // Escape HTML special characters to prevent XSS
            const escapedDescription = description ? description.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            }).replace(/[\r\n]+/g, '<br>') : '';
            
            // Display the description in modal
            $('#descriptionContent').html(`
                <div class="job-description">
                    <hr>
                    <div class="description-text">
                        ${escapedDescription || '<em>{{ __('No description provided.') }}</em>'}
                    </div>
                </div>
            `);
            $('#descriptionModal').modal('show');
        }

        function viewNotes(jobId, notes) {
            // Escape HTML special characters to prevent XSS
            const escapedNotes = notes ? notes.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            }).replace(/[\r\n]+/g, '<br>') : '';
            
            // Display the notes in modal
            $('#notesContent').html(`
                <div class="job-notes">
                    <hr>
                    <div class="notes-text">
                        ${escapedNotes || '<em>{{ __('No notes provided.') }}</em>'}
                    </div>
                </div>
            `);
            $('#notesModal').modal('show');
        }

        // Show job details function
        function showJobDetails(jobId) {
            window.location.href = "{{ route('admin.shop-management.job-details.show', ':id') }}".replace(':id', jobId);
        }

        // Delete function for assigned jobs
        @if(auth('admin')->user()->hasPermissionTo('assign.job.delete'))
        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ route('admin.assigned-jobs.destroy', ':id') }}".replace(':id', id));
        }
        @endif
    </script>
@endpush