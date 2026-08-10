@extends('website.layouts.app')

@section('content')
    @include('components::breadcrumb', ['title' => __('Brands'), 'image' => 'cart'])

    <section class="wsus__brand_page pt_95 xs_pt_75 pb_120 xs_pb_100">
        <div class="container">
            <div class="row ">
                @forelse ($brands as $brand)
                    <div class="col-xl-2 col-6 col-sm-4 col-md-3 wow fadeInUp">
                        <a class="wsus__brand_item"
                            href="{{ route('website.products', [
                                'brand' => $brand->slug,
                            ]) }}">
                            <img class="img-fluid w-100" src="{{ asset($brand->image) }}" alt="category">
                        </a>
                    </div>
                @empty
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__brand_item">
                            <div class="text">
                                <p>{{ __('No brand found') }}!</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    {{ $brands->links('components::pagination') }}
                </div>
            </div>
        </div>
    </section>
@endsection
