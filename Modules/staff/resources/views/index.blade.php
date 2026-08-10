@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQ List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('FAQ List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('FAQ List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('FAQ List')" />
                                @adminCan('faq.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.faq.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Question') }}</th>
                                                <th>{{ __('Answer') }}</th>
                                                <th>{{ __('Group') }}</th>
                                                @adminCan('faq.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('faq.edit') || checkAdminHasPermission('faq.delete'))
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($faqs as $faq)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $faq->question }}</td>
                                                    <td>{{ $faq->answer }}</td>
                                                    <td>{{ $faq->groupEnum->label() }}</td>
                                                    @adminCan('faq.update')
                                                        <td>
                                                            <input id="status_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="changeStatus({{ $faq->id }})"
                                                                {{ $faq->status ? 'checked' : '' }}>
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('faq.edit') || checkAdminHasPermission('faq.delete'))
                                                        <td>
                                                            @adminCan('faq.edit')
                                                                <x-admin.edit-button :href="route('admin.faq.edit', [
                                                                    'faq' => $faq->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('faq.delete')
                                                                <x-admin.delete-button :id="$faq->id" onclick="deleteData" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('FAQ')" route="admin.faq.create" create="yes"
                                                    :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $faqs->onEachSide(3)->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('faq.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('faq.delete')
        "use strict";

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/faq/') }}" + "/" + id)
        }
        @endadminCan

        @adminCan('faq.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/faq/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.message) {
                        toastr.error(err.responseJSON.message);
                    } else {
                        toastr.error("{{ __('Something went wrong, please try again') }}");
                    }
                }
            })
        }
        @endadminCan
    </script>
@endpush
