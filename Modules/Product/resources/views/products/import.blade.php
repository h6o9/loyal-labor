@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Import Products') }}</title>
@endsection

@push('css')
    <link href="{{ asset('backend/css/dropzone.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="main-content">
        <section class="section">

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>
                                    <a href="{{ asset('backend/product.xlsx') }}" download>{{ __('Sample Download') }}</a>
                                </h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.product.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="dropzone" id="mydropzone" action="{{ route('admin.product.import.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="fallback">
                                        <input name="file" type="file" accept=".csv,.xls,.xlsx" />
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="text-center offset-md-2 col-md-8">
                                        <x-admin.save-button id="submitForm" :text="__('Save')">
                                        </x-admin.save-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backend/js/dropzone.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            $('button').click(function() {
                $('#mydropzone').submit();
            });
        });
    </script>
@endpush
