@extends('website.layouts.app')

@section('content')
    @include('components::breadcrumb', ['title' => __('About Us'), 'image' => 'cart'])

    @php
        $aboutUsPage = $sections;
    @endphp

    @if (sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_status') == 'active')
        <!--============================ ABOUT MISSION START ==============================-->
        <section class="wsus__about_mission pt_120 xs_pt_100">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-5 col-xl-5 wow fadeInLeft">
                        <div class="wsus__about_mission_img">
                            <img class="img-fluid w-100"
                                src="{{ asset(sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_image')) }}"
                                alt="mission">
                            <div class="shape_img">
                                <img class="img-fluid w-100"
                                    src="{{ asset(sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_preview_image')) }}"
                                    alt="mission">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-6 wow fadeInRight">
                        <div class="wsus__about_mission_text">
                            <h6>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_subtitle') }}
                            </h6>
                            <h2>{!! sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_title') !!}
                            </h2>
                            <p>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_description') }}
                            </p>
                            <ul>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_1') }}
                                </li>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_2') }}
                                </li>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_3') }}
                                </li>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_4') }}
                                </li>
                            </ul>
                            <h1>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'mission_background_text') }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--============================ABOUT MISSION END==============================-->
    @endif
    @if (sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_status') == 'active')
        <!--============================ABOUT VISSION START==============================-->
        <section class="wsus__about_mission wsus__about_vission pt_120 xs_pt_100 pb_120 xs_pb_100">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6 col-xl-6 wow fadeInLeft">
                        <div class="wsus__about_mission_text">
                            <h6>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_subtitle') }}
                            </h6>
                            <h2>{!! sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_title') !!}
                            </h2>
                            <p>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_description') }}
                            </p>
                            <ul>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_1') }}
                                </li>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_2') }}
                                </li>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_3') }}
                                </li>
                                <li>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_4') }}
                                </li>
                            </ul>
                            <h1>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'vision_background_text') }}
                            </h1>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-5 wow fadeInRight">
                        <div class="wsus__about_mission_img">
                            <img class="img-fluid w-100" src="{{ asset('website/images/vission_img_1.webp') }}"
                                alt="mission">
                            <div class="shape_img">
                                <img class="img-fluid w-100" src="{{ asset('website/images/vission_img_2.webp') }}"
                                    alt="mission">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--============================ABOUT VISSION END==============================-->
    @endif

    @if (sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'testimonial_status') == 'active')
        <!--============================TESTIMONIAL START==============================-->
        <section class="wsus__testimonial pt_120 xs_pt_100 pb_110 xs_pb_90"
            style="background: url({{ asset('website/images/testimonial_bg.webp') }});">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="wsus__section_heading mb_45">
                            <h5>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'testimonial_sub_title') }}
                            </h5>
                            <h2>{!! sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'testimonial_title') !!}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wsus__testimonial_area">
                <div class="row testimonial_slide">
                    @foreach ($testimonials as $testimonial)
                        <div class="col-xl-3 wow fadeInUp">
                            <div class="wsus__testimonial_item">
                                <div class="top">
                                    <span>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $testimonial->rating ? '' : 'text-secondary' }}"
                                                aria-hidden="true"></i>
                                        @endfor
                                    </span>
                                    <p>{{ $testimonial->comment }}</p>
                                </div>
                                <div class="bottom">
                                    <div class="img">
                                        <img class="img-fluid w-100" src="{{ asset($testimonial->image) }}" alt="img">
                                    </div>
                                    <div class="text">
                                        <h5>{{ $testimonial->name }}</h5>
                                        <p>{{ $testimonial->designation }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--============================TESTIMONIAL END==============================-->
    @endif

    @if (sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'blog_status') == 'active')
        <!--============================BLOG START==============================-->
        <section class="wsus__blog pt_120 xs_pt_100 pb_120 xs_pb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="wsus__section_heading heading_center mb_20">
                            <h5>{{ sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'blog_sub_title') }}
                            </h5>
                            <h2>{!! sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'blog_title') !!}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($blogs as $blog)
                        <div class="col-md-6 col-xl-4 wow fadeInUp">
                            <div class="wsus__blog_item">
                                <div class="img">
                                    <img class="img-fluid w-100" src="{{ asset($blog->image) }}" alt="blog">
                                </div>
                                <div class="wsus__blog_item_text">
                                    <ul>
                                        <li>{{ $blog->category->title ?? __('Uncategory') }}</li>
                                        <li>
                                            <span><img class="img-fluid w-100"
                                                    src="{{ asset('website/images/calendar_3.webp') }}"
                                                    alt="icon"></span>
                                            {{ formattedDate($blog->created_at) }}
                                        </li>
                                    </ul>
                                    <a class="title"
                                        href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a>
                                    <a class="link"
                                        href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ __('Read More') }}<i
                                            class="far fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!--============================BLOG END==============================-->
    @endif
    @if (sectionData(sections: $aboutUsPage, sectionName: 'about_us_page', propertyName: 'benefit_status') == 'active')
        <!--============================BENEFITE 2 START==============================-->
        <section class="wsus__benefit_2 pb_120 xs_pb_100">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <ul class="wsus__benefit_2_area">
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_one_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_one_title') }}
                                    </h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_one_sub_title') }}</p>
                                </div>
                            </li>
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_two_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_two_title') }}
                                    </h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_two_sub_title') }}</p>
                                </div>
                            </li>
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_three_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_three_title') }}</h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_three_sub_title') }}</p>
                                </div>
                            </li>
                            <li>
                                <span><img class="img-fluid w-100"
                                        src="{{ asset(sectionData($aboutUsPage, 'about_us_page', 'area_four_icon')) }}"
                                        alt="icon"></span>
                                <div class="text">
                                    <h5 class="title">
                                        {{ sectionData($aboutUsPage, 'about_us_page', 'area_four_title') }}</h5>
                                    <p>{{ sectionData($aboutUsPage, 'about_us_page', 'area_four_sub_title') }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--============================BENEFITE 2 END==============================-->
    @endif
@endsection
