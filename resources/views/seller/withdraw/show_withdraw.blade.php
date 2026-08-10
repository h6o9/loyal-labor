@extends('seller.layouts.master')

@section('title')
    <title>{{ __('My withdraw') }}</title>
@endsection

@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('My withdraw') }}" :list="[
                'Dashboard' => route('seller.dashboard'),
                'My withdraw' => route('seller.my-withdraw.index'),
                'Withdraw Request' => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ __('Withdraw Request') }}</h4>

                                <a class="btn btn-primary" href="{{ route('seller.my-withdraw.index') }}"><i
                                        class="fas fa-wallet"></i>
                                    {{ __('Withdraw History') }}</a>
                            </div>

                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
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
                                        <td width="50%">{{ __('Total amount') }}</td>
                                        <td width="50%">{{ defaultCurrency($withdraw->total_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Withdraw amount') }}</td>
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
                                        <td width="50%">{{ formattedDateTime($withdraw->created_at) }}</td>
                                    </tr>
                                    @if ($withdraw->status == 1)
                                        <tr>
                                            <td width="50%">{{ __('Approved Date') }}</td>
                                            <td width="50%">{{ formattedDateTime($withdraw->approved_date) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td width="50%">{{ __('Account Information') }}</td>
                                        <td width="50%">
                                            {!! clean(nl2br($withdraw->account_info)) !!}
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
