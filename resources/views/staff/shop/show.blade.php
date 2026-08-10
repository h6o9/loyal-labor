@extends('staff.master_layout')
@section('title')
    <title>{{ __('Shop Details') }}</title>
@endsection
@section('staff-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('staff.shop.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ __('Shop Details') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Shop Information') }}</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('staff.shop.edit', $shop->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> {{ __('Edit Shop') }}
                                    </a>
                                </div>
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-striped">
                                            <tr>
                                                <td><strong>{{ __('Shop Name') }}</strong></td>
                                                <td>{{ $shop->shop_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Category') }}</strong></td>
                                                <td>
                                                    @php
                                                        $categoryColors = [
                                                            'electrician' => 'primary',
                                                            'wifi_controller' => 'info',
                                                            'solar' => 'warning',
                                                            'plumber' => 'success',
                                                        ];
                                                        $color = $categoryColors[$shop->category] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge badge-{{ $color }}">
                                                        {{ $shop->category_label }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Owner Name') }}</strong></td>
                                                <td>{{ $shop->owner_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Phone Number') }}</strong></td>
                                                <td>{{ $shop->phone_number }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('WhatsApp Number') }}</strong></td>
                                                <td>{{ $shop->whatsapp_number ?: 'N/A' }}</td>
                                            </tr>
                                            @if($shop->email)
                                            <tr>
                                                <td><strong>{{ __('Email') }}</strong></td>
                                                <td>{{ $shop->email }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-striped">
                                            <tr>
                                                <td><strong>{{ __('Address') }}</strong></td>
                                                <td>{{ $shop->address }}</td>
                                            </tr>
                                            @if($shop->latitude && $shop->longitude)
                                            <tr>
                                                <td><strong>{{ __('Location') }}</strong></td>
                                                <td>
                                                    <small class="text-muted">
                                                        Lat: {{ $shop->latitude }}, Lng: {{ $shop->longitude }}
                                                    </small>
                                                    <br>
                                                    <a href="https://www.google.com/maps?q={{ $shop->latitude }},{{ $shop->longitude }}" 
                                                       target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-map-marker-alt"></i> {{ __('View on Map') }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td><strong>{{ __('Created At') }}</strong></td>
                                                <td>{{ $shop->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ __('Updated At') }}</strong></td>
                                                <td>{{ $shop->updated_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if($shop->about_shop)
                                <div class="mt-4">
                                    <h5>{{ __('About Shop') }}</h5>
                                    <p>{{ $shop->about_shop }}</p>
                                </div>
                                @endif

                                @if($shop->latitude && $shop->longitude)
                                <div class="mt-4">
                                    <h5>{{ __('Location Map') }}</h5>
                                    <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                                </div>
                                @endif

                                @if($shop->photos && $shop->photos->count() > 0)
                                <div class="mt-4">
                                    <h5>{{ __('Shop Photos') }}</h5>
                                    <div class="row">
                                        @foreach($shop->photos as $photo)
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                     alt="Shop Photo" 
                                                     class="card-img-top"
                                                     style="height: 200px; object-fit: cover;"
                                                     data-toggle="modal" 
                                                     data-target="#photoModal" 
                                                     data-photo="{{ asset('storage/' . $photo->photo_path) }}">
                                                <div class="card-body p-2 text-center">
                                                    <!-- <small class="text-muted">
                                                        @if($photo->is_primary)
                                                            <span class="badge badge-primary">{{ __('Primary') }}</span>
                                                        @endif
                                                    </small> -->
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="mt-4">
                                    <a href="{{ route('staff.shop.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back to Shop List') }}
                                    </a>
                                    @if(auth('staff')->user()->hasPermission('shop_management', 'can_edit'))
                                    <a href="{{ route('staff.shop.edit', $shop->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> {{ __('Edit Shop') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Shop Photo Gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalPhoto" src="" alt="Shop Photo" class="img-fluid">
                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">
                            <i class="fas fa-times"></i> {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 300px;
            width: 100%;
            border-radius: 8px;
            z-index: 1;
        }
        .leaflet-control-attribution {
            font-size: 9px;
        }
        .card-img-top {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .card-img-top:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        $(document).ready(function() {
            // Photo modal functionality
            $('img[data-toggle="modal"]').click(function() {
                const photoSrc = $(this).data('photo');
                $('#modalPhoto').attr('src', photoSrc);
                $('#photoModal').modal('show');
            });

            // Close modal function
            window.closePhotoModal = function() {
                $('#photoModal').modal('hide');
            };

            @if($shop->latitude && $shop->longitude)
            // Initialize map
            const map = L.map('map').setView([{{ $shop->latitude }}, {{ $shop->longitude }}], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            const marker = L.marker([{{ $shop->latitude }}, {{ $shop->longitude }}]).addTo(map);
            @endif
        });
    </script>
@endpush
