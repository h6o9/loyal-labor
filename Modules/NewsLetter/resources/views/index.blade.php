@extends('admin.master_layout')
@section('title')
    <title>{{ __('Subscriber List') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Subscriber List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Subscriber List') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="newsletterTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Subscribed at') }}</th>
                                                @adminCan('newsletter.delete')
                                                    <th>{{ __('Action') }}</th>
                                                @endadminCan
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
    @adminCan('newsletter.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
@push('js')
    @include('admin.partials.system-records-toast')
    <script>
        $(document).ready(function() {
            initServerDataTable('#newsletterTable', "{{ route('admin.subscriber-list') }}", [
                { data: 'email_decoded', name: 'email' },
                { data: 'subscribed_at', name: 'created_at' },
                @adminCan('newsletter.delete')
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endadminCan
            ]);
        });
    </script>
@endpush