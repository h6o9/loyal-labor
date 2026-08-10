@extends('admin.master_layout')



@section('title')

    <title>{{ __('App Notifications') }}</title>

@endsection



@section('admin-content')

<div class="main-content">

    <section class="section">

        <div class="section-header d-flex justify-content-between">

            <h1>{{ __('App Notifications') }}</h1>

            <a href="{{ route('admin.app-notifications.create') }}" class="btn btn-primary">

                <i class="fa fa-plus"></i> {{ __('Send Notification') }}

            </a>

        </div>



        <div class="section-body">

            <div class="card">

                <div class="card-body table-responsive">

                    <table class="table table-striped">

                        <thead>

                            <tr>

                                <th>{{ __('Title') }}</th>

                                <th>{{ __('Audience') }}</th>

                                <th>{{ __('Status') }}</th>

                                <th>{{ __('Sent At') }}</th>

                                <th>{{ __('Action') }}</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($notifications as $item)

                                <tr>

                                    <td>{{ $item->title }}</td>

                                    <td>

                                        @if($item->target_audience === 'specific_users')

                                            {{ __('Specific Users') }}

                                            ({{ count($item->target_user_ids ?? []) }})

                                        @else

                                            {{ ucfirst(str_replace('_', ' ', $item->target_audience)) }}

                                        @endif

                                    </td>

                                    <td><span class="badge badge-{{ $item->status === 'sent' ? 'success' : ($item->status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($item->status) }}</span></td>

                                    <td>{{ $item->sent_at ? $item->sent_at->format('Y-m-d H:i') : '-' }}</td>

                                    <td class="text-nowrap">

                                        @if($item->status !== 'sent')

                                            <form action="{{ route('admin.app-notifications.send', $item->id) }}" method="POST" class="d-inline">

                                                @csrf

                                                <button type="submit" class="btn btn-sm btn-success">{{ __('Send Now') }}</button>

                                            </form>

                                        @endif

                                        <form action="{{ route('admin.app-notifications.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this admin notification? User inboxes will stay unchanged.') }}');">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr><td colspan="5" class="text-center text-muted">{{ __('No notifications yet.') }}</td></tr>

                            @endforelse

                        </tbody>

                    </table>

                    {{ $notifications->links() }}

                </div>

            </div>

        </div>

    </section>

</div>

@endsection

