@extends('seller.layouts.master')

@section('title')
    <title>{{ __('All Notifications') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('All Notifications') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('All Notifications') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card mt-5">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('All Notifications')" />
                                <div>
                                    <form action="">
                                        <div class="input-group">
                                            <input class="form-control" name="keyword" type="text"
                                                value="{{ request()->get('keyword') }}" placeholder="{{ __('Search') }}">

                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Order') }}</th>
                                                <th>{{ __('From') }}</th>
                                                <th>{{ __('To') }}</th>
                                                <th>{{ __('By') }}</th>
                                                <th>{{ __('Updated At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($allNotifications as $orderStatusHistory)
                                                <tr>
                                                    <td>
                                                        {{ $loop->index + 1 }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $orderStatusHistory->type == 'order_status' ? 'badge-success' : 'badge-info' }}">
                                                            {{ str($orderStatusHistory->type)->replace('_', ' ')->upper() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('seller.orders.show', ['id' => $orderStatusHistory->order_id]) }}">
                                                            #{{ $orderStatusHistory->order->order_id ?? 'N/A' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $orderStatusHistory->from_status_enum->getLabel() }}</td>
                                                    <td>{{ $orderStatusHistory->to_status_enum->getLabel() }}</td>
                                                    <td>
                                                        @if ($orderStatusHistory->change_by == 'admin')
                                                            {{ $orderStatusHistory->changedByAdmin->name ?? '' }}
                                                        @elseif($orderStatusHistory->change_by == 'user')
                                                            {{ $orderStatusHistory->changedByUser->name ?? '' }}
                                                        @else
                                                            {{ $orderStatusHistory->change_by }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ formattedDateTime($orderStatusHistory->updated_at ? $orderStatusHistory->updated_at : $orderStatusHistory->created_at) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="4">
                                                        {{ __('No Order Status History Found') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="d-flex justify-content-center mt-5">
                                        {{ $allNotifications->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
