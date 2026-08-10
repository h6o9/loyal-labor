@extends('admin.master_layout')

@section('title')
    <title>
        {{ __('Notifications') }}
    </title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>{{ __('Notifications') }}</h1>
                @if ($setting->is_queueable == config('services.default_status.active_text'))
                    <div>
                        <p class="badge badge-info">
                            {{ __('Read notifications from the previous month will be automatically deleted once a month.') }}
                        </p>
                    </div>
                @endif
                <a class="btn btn-success" href="{{ route('admin.settings') }}">{{ __('Back') }}</a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="filter_form" action="{{ route('admin.notifications.index') }}" method="get">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input class="form-control" id="search" name="search" type="text"
                                                value="{{ request('search') }}" placeholder="{{ __('Search') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="type" name="type">
                                                <option value="" @if (request('type') == 'all') selected @endif>
                                                    {{ __('All') }} ({{ __('Read') . ': ' . $readCount ?? 0 }} +
                                                    {{ __('Unread') . ': ' . $unreadCount ?? 0 }})</option>
                                                <option class="text-success" value="read"
                                                    @if (request('type') == 'read') selected @endif>
                                                    {{ __('Read') }}: {{ $readCount ?? 0 }}</option>
                                                <option class="text-danger" value="unread"
                                                    @if (request('type') == 'unread') selected @endif>
                                                    {{ __('Unread') }}: {{ $unreadCount ?? 0 }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="alert_type" name="alert_type">
                                                <option value="" @if (request('alert_type') == 'all') selected @endif>
                                                    {{ __('All Alert Types') }}</option>
                                                <option class="text-info" value="info"
                                                    @if (request('alert_type') == 'info') selected @endif>
                                                    {{ __('Info') }}: {{ $infoCount ?? 0 }}</option>
                                                <option class="text-success" value="success"
                                                    @if (request('alert_type') == 'success') selected @endif>
                                                    {{ __('Success') }}: {{ $successCount ?? 0 }}</option>
                                                <option class="text-warning" value="warning"
                                                    @if (request('alert_type') == 'warning') selected @endif>
                                                    {{ __('Warning') }}: {{ $warningCount ?? 0 }}</option>
                                                <option class="text-danger" value="danger"
                                                    @if (request('alert_type') == 'danger') selected @endif>
                                                    {{ __('Danger') }}: {{ $dangerCount ?? 0 }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="order" name="order">
                                                <option value="asc" @if (request('order') == 'asc') selected @endif>
                                                    {{ __('Ascending') }}</option>
                                                <option value="desc" @if (request('order') == 'desc') selected @endif>
                                                    {{ __('Descending') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('All Notifications') }} ({{ $totalNotificationsCount ?? 0 }})</h4>
                                <div class="card-header-form d-flex justify-content-end">
                                    <button class="btn btn-success m-1 confirm"
                                        data-href="{{ route('admin.notifications.mark-as-read') }}"
                                        type="button">{{ __('Mark all as read') }}:
                                        <span id="mark_read_count">{{ $unreadCount ?? 0 }}</span></button>
                                    <button class="btn btn-danger m-1 confirm"
                                        data-href="{{ route('admin.notifications.delete-all') }}"
                                        type="button">{{ __('Delete All') }}:
                                        <span id="delete_all_count">{{ $totalNotificationsCount ?? 0 }}</span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-hover" id="notificationsTable" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th width="1%">
                                                    <input class="form-check-input" id="select_all" type="checkbox">
                                                </th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Created at') }}</th>
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

    <x-admin.delete-modal />

    @push('js')
        <script src="{{ asset('global/js/sweetalert.js') }}"></script>

        <script>
            "use strict";

            function deleteData(id) {
                $("#deleteForm").attr("action", "{{ url('/admin/notifications/destroy') }}" + "/" + id)
            }

            var notificationsTable = initServerDataTable('#notificationsTable', {
                url: "{{ route('admin.notifications.index') }}",
                data: function(d) {
                    d.search = $('#search').val();
                    d.type = $('#type').val();
                    d.alert_type = $('#alert_type').val();
                    d.order = $('#order').val();
                }
            }, [
                { data: 'select', name: 'select', orderable: false, searchable: false },
                { data: 'title_col', name: 'title' },
                { data: 'created_at_col', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);

            $('#filter_form').on('change', function(e) {
                e.preventDefault();
                notificationsTable.ajax.reload();
            });

            $('#select_all').on('click', function() {
                if (this.checked) {
                    $('.select_single').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.select_single').each(function() {
                        this.checked = false;
                    });
                }

                updateMarkReadCount();
                updateDeleteAllCount();
            });

            $(document).on('click', '.select_single', function() {
                if ($('.select_single:checked').length == $('.select_single').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }

                updateMarkReadCount();
                updateDeleteAllCount();
            });

            $('#notificationsTable').on('draw.dt', function() {
                $('#select_all').prop('checked', false);
                updateMarkReadCount();
                updateDeleteAllCount();
            });

            function updateMarkReadCount() {
                var selectedIds = [];
                $('.select_single:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });
                if (selectedIds.length == 0) {
                    $('#mark_read_count').text("{{ $unreadCount ?? 0 }}");
                } else {
                    $('#mark_read_count').text(selectedIds.length);
                }

            }

            function updateDeleteAllCount() {
                var selectedIds = [];
                $('.select_single:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });
                if (selectedIds.length == 0) {
                    $('#delete_all_count').text("{{ $unreadCount ?? 0 }}");
                } else {
                    $('#delete_all_count').text(selectedIds.length);
                }
            }

            $(".confirm").on('click', function(e) {
                e.preventDefault();
                var url = $(this).data('href');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('You would not be able to revert this!') }}",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#3085d6",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "{{ __('Yes!') }}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        var selectedIds = [];

                        $('.select_single:checked').each(function() {
                            selectedIds.push($(this).data('id'));
                        });

                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(result) {
                                location.reload();
                            },
                            error: function(err) {
                                toastr.error(err.responseJSON.message);
                                console.log(err);
                            }
                        })
                    }
                });
            });
        </script>
    @endpush
@endsection
