@extends('admin.master_layout')
@section('title')
    <title>{{ __('Agent List') }}</title>
@endsection
@section('admin-content')
    @can('staff.view')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Agent List') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Agent List') }} <small class="font-weight-bold text-danger"> ({{ __('The default password for all agent members is 12345678. This password is automatically generated when a new agent member is created.') }})</small></h4>
                                @can('staff.create')
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.staff.create') }}"><i
                                            class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                </div>
                                @endcan
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="district_filter">{{ __('Filter by District') }}</label>
                                            <select id="district_filter" class="form-control select2">
                                                <option value="">{{ __('All Districts') }}</option>
                                                @if(isset($districts))
                                                    @foreach($districts as $district)
                                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="staffTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('District') }}</th>
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
    @else
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Access Denied') }}</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    {{ __('You do not have permission to view staff list.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @endcan
@endsection

@push('js')
    <!-- Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        "use strict";

        $(document).ready(function() {
            // Initialize Select2 for district filter
            $('.select2').select2({
                placeholder: "{{ __('Search district...') }}",
                allowClear: true,
                width: '100%'
            });

            var staffTable = initServerDataTable('#staffTable', {
                url: "{{ route('admin.staff.index') }}",
                data: function(d) {
                    d.district_id = $('#district_filter').val();
                }
            }, [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'district_name', name: 'district.name', orderable: false },
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ], {
                drawCallback: function () {
                    // Toggles already initialized by initSimpleAjaxTable → initBootstrapToggles.
                    // Do NOT call .bootstrapToggle() again here (double-wrap breaks clicks).
                }
            });

            // District filtering functionality (server-side now)
            $('#district_filter').on('change', function() {
                staffTable.ajax.reload();
            });
        });

        function changeStaffStatus(staffId) {
            let status = $('#status_toggle_' + staffId).prop('checked') ? 1 : 0;
            let url = '{{ route("staff.change.status", ":id") }}'.replace(':id', staffId);

            // #region agent log
            fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'STAFF',location:'staff/index.blade.php:changeStaffStatus',message:'staff status toggle clicked',data:{staffId:staffId,status:status,url:url},timestamp:Date.now()})}).catch(function(){});
            // #endregion

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // #region agent log
                    fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'STAFF',location:'staff/index.blade.php:success',message:'staff status ajax success',data:{staffId:staffId,response:response},timestamp:Date.now()})}).catch(function(){});
                    // #endregion
                    if(response.success) {
                        toastr.success('Updated successfully');
                    } else {
                        toastr.error('Something went wrong');
                        $('#status_toggle_' + staffId).bootstrapToggle(status == 1 ? 'off' : 'on');
                    }
                },
                error: function(xhr) {
                    // #region agent log
                    fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'STAFF',location:'staff/index.blade.php:error',message:'staff status ajax error',data:{staffId:staffId,status:xhr.status,responseText:(xhr.responseText||'').substring(0,300)},timestamp:Date.now()})}).catch(function(){});
                    // #endregion
                    toastr.error('Error updating status');
                    $('#status_toggle_' + staffId).bootstrapToggle(status == 1 ? 'off' : 'on');
                }
            });
        }
    </script>
@endpush