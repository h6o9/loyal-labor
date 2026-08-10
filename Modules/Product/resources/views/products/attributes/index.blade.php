@extends('admin.master_layout')
@section('title')
    <title>{{ __('Attribute List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Attribute List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Attribute List') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Attribute List')" />
                                @adminCan('product.attribute.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.attribute.create')" text="{{ __('Add Attribute') }}" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table" id="attributesTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Name') }}</th>
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

    <div class="modal fade" id="confirm-availibility" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <input name="attribute_id" type="hidden">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">
                            {{ __('Attribute has values. Sure to Delete?') }}
                        </p>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" data-bs-dismiss="modal" type="button">{{ __('Close') }}</button>
                        <button class="btn btn-danger" type="submit">{{ __('Yes, Delete') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            initServerDataTable('#attributesTable', "{{ route('admin.attribute.index') }}", [
                { data: 'name', name: 'name', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]);
        });

        $(document).ready(function() {
            'use strict';

            $(document).on('click', '.deleteValue', function() {
                $('.preloader_area').removeClass('d-none')

                const id = $(this).data('id');

                const route = "{{ route('admin.attribute.destroy', ':id') }}".replace(':id', id);
                $.ajax({
                    url: "{{ route('admin.attribute.has-value') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        attribute_id: id
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#confirm-availibility').find('form').attr('action', route);
                            $('[name="attribute_id"]').val(id);
                            $('#confirm-availibility').modal('show');
                        } else {
                            $('#confirm-availibility').find('form').attr('action', route);
                            $('[name="attribute_id"]').val(id);
                            $('#confirm-availibility').modal('show');
                        }

                        $('.preloader_area').addClass('d-none')
                    }
                });
            });
        });
    </script>
@endpush
