@extends('website.layouts.app')

@section('title')
    {{ __('Blogs') }} - {{ $setting->app_name }}
@endsection

@section('content')
    {{-- <!--============================
        BREADCRUMBS START
    ==============================--> --}}
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Blogs') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Blogs') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <!--============================
        BREADCRUMBS END
    ==============================--> --}}

    {{-- <!--============================
        BLOG PAGE START
    ==============================--> --}}
    <section class="wsus__blog pt_95 xs_pt_75 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @forelse ($blogs as $blog)
                    <div class="col-md-6 col-xl-4 wow fadeInUp">
                        @include('components::blog-' . config('services.theme'))
                    </div>
                @empty
                    <div class="col-md-12 col-xl-12 wow fadeInUp">
                        {{ __('No Data Found') }}
                    </div>
                @endforelse

                @if ($blogs->hasPages())
                    <div class="row">
                        <div class="col-12 wow fadeInUp">
                            {{ $blogs->links('components::pagination') }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
    {{-- <!--============================
        BLOG PAGE END
    ==============================--> --}}
@endsection
