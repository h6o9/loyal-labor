@extends('website.layouts.app')

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Catagories') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Catagories') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wsus__category_page pt_95 xs_pt_75 pb_120 xs_pb_100">
        <div class="container">
            <div class="row ">
                @forelse ($categories as $category)
                    <div
                        class="{{ config('services.theme') == 4 ? 'col-xl-2 col-sm-6 col-lg-4' : 'col-xl-2 col-6 col-sm-4 col-md-3' }} wow fadeInUp">
                        @include('components::category-' . config('services.theme'))
                    </div>
                @empty
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__category_item">
                            <div class="text">
                                <p>{{ __('No Catagory Found') }}!</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    {{ $categories->links('components::pagination') }}
                </div>
            </div>
        </div>
    </section>
@endsection
