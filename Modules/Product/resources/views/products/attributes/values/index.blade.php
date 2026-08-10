@extends('admin.master_layout')
@section('title')
    <title>{{ __('Attribute Detail') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Attribute Detail') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Attribute Detail') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Attribute Detail')" />
                                @adminCan('product.attribute.create')
                                    <div>
                                        <x-admin.add-button data-bs-toggle="modal" data-bs-target="#add-attribute-value"
                                            href="javascript:;" text="{{ __('Add Values') }}" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table" id="attributeValuesTable" style="width:100%">
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

    <div class="modal fade" id="add-attribute-value" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.attributes.values.store') }}" method="POST">
                @csrf
                <input name="attribute_id" type="hidden" value="{{ $attribute->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New Attribute Value') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="attribute">{{ __('Attribute Name') }} <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="attribute" name="attribute" type="text"
                                        value="{{ $attribute->name }}" required readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">{{ __('Attribute Value') }} <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="name" name="name" type="text"
                                        value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-bs-dismiss="modal" type="button">{{ __('Close') }}</button>
                        <button class="btn btn-success" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            initServerDataTable('#attributeValuesTable', "{{ route('admin.attributes.values.index') }}", [
                { data: 'name', name: 'name', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ], {
                ajax: {
                    url: "{{ route('admin.attributes.values.index') }}",
                    data: function(d) {
                        d.attribute_id = {{ (int) $attribute->id }};
                    }
                }
            });
        });

        function deleteData(id) {
            const route = "{{ route('admin.attributes.values.destroy', ':id') }}".replace(':id', id);
            $("#deleteForm").attr("action", route)
        }
    </script>
@endpush
