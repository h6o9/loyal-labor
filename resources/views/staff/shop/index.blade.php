@extends('staff.master_layout')
@section('title')
    <title>{{ __('Shop List') }}</title>
@endsection
@section('staff-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Shop List') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Shop List') }}</h4>
                                @if(auth('staff')->user()->hasPermission('shop_management', 'can_create'))
                                <div>
                                    <a class="btn btn-primary" href="{{ route('staff.shop.create') }}">
                                        <i class="fa fa-plus"></i> {{ __('Add New Shop') }}
                                    </a>
                                </div>
                                @endif
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="mb-3 px-4 pt-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label for="category_filter">{{ __('Filter by Category') }}</label>
                                                <select id="category_filter" name="category" class="form-control select2">
                                                    <option value="">{{ __('All Categories') }}</option>
                                                    @if(isset($categories))
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->name }}">{{ __($category->name) }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="shopTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Reference Code') }}</th>
                                                <th>{{ __('Reference Counter') }}</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Owner Name') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Location') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('WhatsApp') }}</th>
                                                <th>{{ __('District') }}</th>
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
@endsection

@push('js')
    <!-- SweetAlert2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        "use strict";
        
        $(document).ready(function() {

            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
            }
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Auto-hide alerts after 3 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);

            var shopTable = initServerDataTable('#shopTable', "{{ route('staff.shop.index') }}", [
                { data: 'reference_code', name: 'reference_code' },
                { data: 'technicians_count', name: 'registered_technicians_count' },
                { data: 'shop_name', name: 'shop_name' },
                { data: 'owner_name', name: 'owner_name' },
                { data: 'category_badge', name: 'category' },
                { data: 'location', name: 'location' },
                { data: 'phone_number', name: 'phone_number' },
                { data: 'whatsapp_number', name: 'whatsapp_number' },
                { data: 'district_name', name: 'district_name', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ], {
                ajax: {
                    url: "{{ route('staff.shop.index') }}",
                    data: function (d) {
                        d.category = $('#category_filter').val();
                    }
                }
            });

            $('#category_filter').on('change', function() {
                shopTable.ajax.reload();
            });
        });

        // Photo Modal Function
        function openPhotoModal(photoSrc) {
            Swal.fire({
                imageUrl: photoSrc,
                imageAlt: 'Shop Photo',
                showCloseButton: true,
                showConfirmButton: false,
                width: '80%',
                imageWidth: '100%',
                imageHeight: 'auto',
                padding: '1rem'
            });
        }

        // Success and Error messages with Toastr
        @if(session('success'))
            if(typeof toastr !== 'undefined') {
                toastr.success('{{ session('success') }}', 'Success!');
            } else {
                // Fallback to SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        @endif
        
        @if(session('error'))
            if(typeof toastr !== 'undefined') {
                toastr.error('{{ session('error') }}', 'Error!');
            } else {
                // Fallback to SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        @endif
    </script>
@endpush

@push('css')
    <style>
        .img-thumbnail {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .img-thumbnail:hover {
            transform: scale(1.5);
            z-index: 10;
            position: relative;
        }
        .badge {
            font-size: 12px;
            padding: 5px 8px;
        }
        .btn-group .btn {
            margin: 0 2px;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush