@extends('website.layouts.app')

@section('title')
    {{ __('Frequently Asked Questions') }} - {{ $setting->app_name }}
@endsection

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ __('Faq\'s') }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Faq\'s') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wsus__faq_page pt_110 xs_pt_90 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    @foreach ($faqs->groupBy('group') as $key => $faqGroup)
                        <div class="wsus__faq_contant {{ !$loop->first ? 'mt-5' : '' }} wow fadeInUp">
                            <h2>{{ $enum::tryFrom($key)->label() ?? '' }}</h2>
                        </div>
                        <div class="accordion accordion-flush wsus__faq_accordion" id="accordion_{{ $key }}">
                            @forelse ($faqGroup as $faq)
                                <div class="accordion-item wow fadeInUp">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse_{{ $faq->id }}" type="button"
                                            aria-expanded="false" aria-controls="flush-collapse_{{ $faq->id }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div class="accordion-collapse collapse" id="flush-collapse_{{ $faq->id }}"
                                        data-bs-parent="#accordion_{{ $key }}">
                                        <div class="accordion-body">{!! clean($faq->answer) !!}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center">
                                    <h4>{{ __('No Data Found') }}</h4>
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
