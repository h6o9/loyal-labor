@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Sub Admin') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Sub Admins') }}" :list="[
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Manage Sub Admins')" /><small class="font-weight-bold text-danger">(The default password for all sub-admins is 12345678. This password is automatically generated when a new sub-admin is created. The Admin Panel will only be accessible to a sub-admin once a role has been assigned to them.)</small>
                                <div>
                                    @adminCan('admin.create')
                                        <x-admin.add-button :href="route('admin.admin.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="adminTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    @adminCan('admin.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var adminTable = initServerDataTable('#adminTable', {
                url: "{{ route('admin.admin.index') }}"
            }, [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                drawCallback: function () {
                    // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
                }
            });
        });

        // #region agent log
        window.__adminStatusFnDefined = true;
        fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'B',location:'admin.blade.php:ready',message:'changeAdminStatus availability',data:{fnDefined:typeof changeAdminStatus==='function',hasAdminUpdatePerm:{{ auth('admin')->user() && auth('admin')->user()->can('admin.update') ? 'true' : 'false' }},hasAdminEditPerm:{{ auth('admin')->user() && auth('admin')->user()->can('admin.edit') ? 'true' : 'false' }},statusUrl:"{{ url('/admin/admin-status/') }}",routeUrl:"{{ route('admin.admin.status', ['id' => 999]) }}"},timestamp:Date.now()})}).catch(function(){});
        // #endregion

        @adminCan('admin.update')

        function changeAdminStatus(id) {
            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
            var statusUrl = "{{ url('/admin/admin-status/') }}" + "/" + id;
            // #region agent log
            fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'C',location:'admin.blade.php:changeAdminStatus',message:'admin status toggle clicked',data:{id:id,statusUrl:statusUrl,method:'put',isDemo:isDemo,checked:$('#status_toggle_'+id).prop('checked')},timestamp:Date.now()})}).catch(function(){});
            // #endregion
            if (isDemo == 'DEMO') {
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: statusUrl,
                success: function(response) {
                    // #region agent log
                    fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'D',location:'admin.blade.php:success',message:'admin status ajax success',data:{id:id,response:response},timestamp:Date.now()})}).catch(function(){});
                    // #endregion
                    toastr.success(response.message)
                },
                error: function(err) {
                    // #region agent log
                    fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'D',location:'admin.blade.php:error',message:'admin status ajax error',data:{id:id,status:err.status,statusText:err.statusText,responseText:(err.responseText||'').substring(0,300)},timestamp:Date.now()})}).catch(function(){});
                    // #endregion
                    console.log(err);
                }
            })
        }
        @endadminCan

        // #region agent log
        if (typeof changeAdminStatus !== 'function') {
            window.changeAdminStatus = function(id) {
                fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'B',location:'admin.blade.php:missingFn',message:'changeAdminStatus MISSING - admin.update permission block skipped',data:{id:id},timestamp:Date.now()})}).catch(function(){});
                console.error('changeAdminStatus is not defined (likely missing admin.update permission)');
            };
        }
        // #endregion
    </script>
@endpush
