@extends('admin.master_layout')
@section('title')
    <title>{{ __('Notification Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>{{ __('Notification Details') }}</h1>
                <a class="btn btn-success" href="{{ route('admin.notifications.index') }}">{{ __('Back') }}</a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-hover">
                                    <tr>
                                        <td>{{ __('Title') }}</td>
                                        <td>{{ htmlDecode($notification->title) }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Type') }}</td>
                                        <td>{{ str($notification->type)->title() }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Message') }}</td>
                                        <td>{{ nl2br(htmlDecode($notification->message)) }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Created at') }}</td>
                                        <td>{{ formattedDateTime($notification->created_at) }}
                                            ({{ $notification->created_at->diffForHumans() }})</td>
                                    </tr>

                                    @if ($notification->is_read == 1)
                                        <tr>
                                            <td>{{ __('Read at') }}</td>
                                            <td>{{ formattedDateTime($notification->updated_at) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td>{{ __('Action') }}</td>
                                        <td>
                                            @if ($notification->link)
                                                <a class="btn btn-success btn-sm" href="{{ $notification->link }}"><i
                                                        class="fas fa-link"></i>
                                                    {{ __('View') }}</a>
                                            @endif
                                            <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" href="javascript:;"
                                                onclick="deleteData({{ $notification->id }})"><i class="fas fa-trash"></i>
                                                {{ __('Delete') }}</a>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <x-admin.delete-modal />
    @push('js')
        <script>
            function deleteData(id) {
                $("#deleteForm").attr("action", "{{ url('/admin/notifications/destroy') }}" + "/" + id)
            }
        </script>
    @endpush
@endsection
