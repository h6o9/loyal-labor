@extends('admin.master_layout')
@section('title')
    <title>{{ __('Withdraw Details') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Withdraw Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Withdraw Details') => '#',
            ]" />

            <div class="section-body">
                <a class="btn btn-primary" href="{{ route('admin.withdraw-list') }}"><i class="fas fa-list"></i>
                    {{ __('Seller Withdraw') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <td width="50%">{{ __('influencer') }}</td>
                                        <td width="50%">
                                            <a
                                                href="{{ route('admin.customer-show', $withdraw->user_id) }}">{{ $withdraw?->user?->name }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Withdraw Method') }}</td>
                                        <td width="50%">{{ $withdraw->method }}</td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Withdraw Charge') }}</td>
                                        <td width="50%">{{ $withdraw->withdraw_charge }}%</td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Withdraw Charge Amount') }}</td>
                                        <td width="50%">
                                            {{ defaultCurrency($withdraw->total_amount - $withdraw->withdraw_amount) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Total Amount') }}</td>
                                        <td width="50%">
                                            {{ defaultCurrency($withdraw->total_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Withdraw Amount') }}</td>
                                        <td width="50%">
                                            {{ defaultCurrency($withdraw->withdraw_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Status') }}</td>
                                        <td width="50%">
                                            @if ($withdraw->status == 'approved')
                                                <span class="badge bg-success">{{ __('Approved') }}</span>
                                            @elseif ($withdraw->status == 'rejected')
                                                <span
                                                    class="badge bg-danger">{{ __('Rejected') }}</span>
                                            @else
                                                <span
                                                    class="badge bg-warning">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Requested Date') }}</td>
                                        <td width="50%">{{ formattedDate($withdraw->created_at) }}</td>
                                    </tr>
                                    @if ($withdraw->status == 'approved')
                                        <tr>
                                            <td width="50%">{{ __('Approved Date') }}</td>
                                            <td width="50%">{{ $withdraw->approved_date }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td width="50%">{{ __('Account Information') }}</td>
                                        <td width="50%">
                                            {!! clean(nl2br($withdraw->account_info)) !!}
                                        </td>
                                    </tr>

                                </table>

                                @if ($withdraw->status == 'pending')
                                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#withdrawApproved"
                                        href="javascript:;">{{ __('Approve withdraw') }}</i></a>
                                @endif

                                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    href="javascript:;"
                                    onclick="deleteData({{ $withdraw->id }})">{{ __('Delete withdraw request') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <div class="modal fade" id="withdrawApproved" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Withdraw Approved Confirmation') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are You sure approved this withdraw request ?') }}</p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <form action="{{ route('admin.approved-withdraw', $withdraw->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Approve') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-admin.delete-modal />
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/delete-withdraw/') }}' + "/" + id)
        }
    </script>
@endsection
