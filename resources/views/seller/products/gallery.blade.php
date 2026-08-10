@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Product Gallery Images') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Product Gallery Images') }}</h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('seller.product.index') }}"><i
                                            class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="text-center col-md-12 ">
                                        <form class="dropzone" id="dropzoneForm" method="post"
                                            action="{{ route('seller.product-gallery.store', ['id' => $product->id]) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <button class="btn btn-success" id="submit-all" type="button"><i
                                            class="fas fa-upload"></i>
                                        {{ __('Upload All') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if ($product?->gallery->count() > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('Gallery') }}</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Preview') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($product->gallery as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        @if ($item->path)
                                                            <img class="m-1 img-thumbnail" src="{{ $item->url }}"
                                                                alt="" height="100px" width="100px">
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal" href="javascript:;"
                                                                onclick="deleteData({{ $item->id }})"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Gallery')" route="admin.product.index" create="no"
                                                    :message="__('No data found!')" colspan="4">
                                                </x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>
@endsection

@push('css')
    <link href="{{ asset('global/dropzone/dropzone.min.css') }}" rel="stylesheet">
    <style>
        .dropzone {
            background: white;
            border-radius: 5px;
            border: 2px dashed rgb(0, 135, 247);
            border-image: none;
            max-width: 805px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush
@push('js')
    <script src="{{ asset('global/dropzone/dropzone.min.js') }}"></script>
    <script type="text/javascript">
        function deleteData(id) {
            let url = '{{ route('seller.product-gallery.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr("action", url);
        }
        Dropzone.options.dropzoneForm = {
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            thumbnailHeight: 200,
            thumbnailWidth: 200,
            maxFilesize: 1,
            filesizeBase: 1000,
            addRemoveLinks: true,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.webp,.svg",
            init: function() {
                myDropzone = this;
                $('#submit-all').on('click', function(e) {
                    e.preventDefault();
                    myDropzone.processQueue();
                });

                this.on("complete", function() {
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        var _this = this;
                        _this.removeAllFiles();
                    }
                });
            },
            success: function(file, response) {
                toastr.success(response.message, 'Success');
                setTimeout(() => {
                    window.location.href = response.url;
                }, 2000);
            },
            error: function(file, response) {
                toastr.error(response.message, 'Error');
            }
        };
    </script>
@endpush
