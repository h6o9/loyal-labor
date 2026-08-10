@extends('admin.master_layout')

@section('title')
    <title>{{ __('District List') }}</title>
@endsection

@section('admin-content')
@can('district.view')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('District List') }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ __('District List') }}</h4>

                            @can('district.create')
                            <a class="btn btn-primary" href="{{ route('admin.districts.create') }}">
                                <i class="fa fa-plus"></i> {{ __('Add New') }}
                            </a>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="districtTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('District Name') }}</th>
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

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ __('Delete District') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                {{ __('Are you sure you want to delete this district?') }}
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@else
<div class="main-content">
    <div class="alert alert-danger m-4">
        {{ __('Access Denied') }}
    </div>
</div>
@endcan
@endsection


@push('js')


<script>
"use strict";

$(document).ready(function() {
    initServerDataTable('#districtTable', "{{ route('admin.districts.index') }}", [
        { data: 'name', name: 'name' },
        { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ], {
        drawCallback: function () {
            // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
        }
    });
});

// DELETE
function deleteData(id) {
    let url = '{{ route("admin.districts.destroy", ":id") }}';
    url = url.replace(':id', id);
    $('#deleteForm').attr('action', url);
}

// DELETE SUBMIT
$('#deleteForm').submit(function(e){
    e.preventDefault();

    let form = $(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(res){
            if(res.success){
                $('#deleteModal').modal('hide');
                toastr.success(res.message);
                setTimeout(()=> location.reload(), 1000);
            } else {
                toastr.error(res.message);
            }
        },
        error: function(){
            toastr.error('Delete failed');
        }
    });
});


// STATUS CHANGE
function changeDistrictStatus(id) {

    let toggle = $('#status_toggle_' + id);
    let status = toggle.prop('checked') ? 'active' : 'inactive';
    let url = '{{ route("admin.districts.change-status", ":id") }}'.replace(':id', id);

    // #region agent log
    fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'E',location:'districts/index.blade.php:changeDistrictStatus',message:'district status toggle clicked',data:{id:id,status:status,url:url,toggleExists:toggle.length,checked:toggle.prop('checked')},timestamp:Date.now()})}).catch(function(){});
    // #endregion

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            status: status,
            _token: '{{ csrf_token() }}'
        },
        success: function(res){
            // #region agent log
            fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'E',location:'districts/index.blade.php:success',message:'district status ajax success',data:{id:id,res:res},timestamp:Date.now()})}).catch(function(){});
            // #endregion
            if(res.success){
                toastr.success('Status updated');
            } else {
                toastr.error('Error');
                toggle.bootstrapToggle(status == 'active' ? 'off' : 'on');
            }
        },
        error: function(xhr){
            // #region agent log
            fetch('http://127.0.0.1:7600/ingest/506040b6-b118-437d-ac00-d7a82a44f1c6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'8fd336'},body:JSON.stringify({sessionId:'8fd336',runId:'pre-fix',hypothesisId:'E',location:'districts/index.blade.php:error',message:'district status ajax error',data:{id:id,status:xhr.status,statusText:xhr.statusText,responseText:(xhr.responseText||'').substring(0,300)},timestamp:Date.now()})}).catch(function(){});
            // #endregion
            toastr.error('Server error');
            toggle.bootstrapToggle(status == 'active' ? 'off' : 'on');
        }
    });
}

</script>

@endpush