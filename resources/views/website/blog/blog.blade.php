@extends('website.layouts.app')

@section('title')
    {{ $blog->title }} - {{ $setting->app_name }}
@endsection

@push('meta')
    <meta name="title" content="{{ $blog->seo_title }}">
    <meta name="description" content="{{ $blog->seo_description }}">
@endpush

@section('content')
    <section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
        <div class="wsus__breadcrumbs_overly">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>{{ $blog->title }}</h1>
                            <ul>
                                <li>
                                    <a href="{{ route('website.home') }}"><i
                                            class="fas fa-home-lg"></i>{{ __('Home') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('website.blogs') }}">{{ __('Blogs') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wsus__blog_details mt_120 xs_mt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 wow fadeInLeft">
                    <div class="wsus__blog_details_area">
                        @if ($blog->image)
                            <div class="wsus__blog_details_thumb">
                                <img class="img-fluid w-100" src="{{ asset($blog->image) }}" alt="Blog">
                            </div>
                        @endif
                        <div class="wsus__blog_details_header">
                            <ul class="d-flex flex-wrap">
                                <li>
                                    <span class="author">
                                        <img class="img-fluid"
                                            src="{{ optional($blog->admin)->image ? asset($blog->admin->image) : asset($setting->default_avatar) }}"
                                            alt="user">
                                    </span>
                                    {{ __('By') }} {{ $blog->admin->name ?? __('Admin') }}
                                </li>
                                <li>
                                    <i class="fal fa-calendar-alt"></i>
                                    {{ formattedDate($blog->created_at) }}
                                </li>
                                <li>
                                    <i class="far fa-bookmark"></i>
                                    {{ $blog->category->title ?? __('Uncategorized') }}
                                </li>
                                <li>
                                    <i class="far fa-comment-alt-dots"></i>
                                    {{ $blog->comments_count ?? 0 }} {{ __('Comments') }}
                                </li>
                            </ul>
                            <h2>{{ htmlDecode(str($blog->title)->title()) }}</h2>
                        </div>
                        <div class="wsus__blog_details_text">
                            {!! clean($blog->description) !!}
                        </div>
                        <div class="wsus__blog_det_tags_share d-flex flex-wrap mt_50">
                            <ul class="tags d-flex flex-wrap align-items-center">
                                <li><span>{{ __('Tags') }}:</span></li>
                                @foreach (json_decode($blog->tags ?? [], true) as $tag)
                                    <li><a
                                            href="{{ route('website.blogs', [
                                                'tag' => $tag['value'],
                                            ]) }}">{{ $tag['value'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <ul class="share d-flex flex-wrap align-items-center">
                                <li><span>{{ __('Share') }}:</span></li>
                                @php
                                    $url = route('website.blog', $blog->slug);
                                    $title = urlencode($blog->title);
                                    $shareUrl = urlencode($url);
                                @endphp

                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                                        target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>

                                <li>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $title }}"
                                        target="_blank">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>

                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $title }}"
                                        target="_blank">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>

                                <li>
                                    <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $title }}"
                                        target="_blank">
                                        <i class="fab fa-pinterest-p"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="wsus__blog_comment_area mt_75">
                            <h2>{{ __('Comments') }}</h2>
                            @if ($comments->isEmpty())
                                <p>{{ __('There are no comments yet') }}.</p>
                            @else
                                @foreach ($comments as $blogComment)
                                    @include('website.blog.comment', ['comment' => $blogComment])
                                @endforeach

                                <div class="d-flex justify-content-end mt-3">
                                    {{ $comments->links() }}
                                </div>
                            @endif
                        </div>
                        <div class="wsus__blog_comment_input_area mt_75">
                            <h2>{{ __('Post a Comment') }}</h2>
                            <p>{{ __('Your email address will not be published. Required fields are marked') }} *</p>
                            <form id="commentForm" action="{{ route('website.blog.comment.store', $blog->slug) }}"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6">
                                        <input name="name" type="text" value="{{ auth()->user()?->name }}"`1
                                            placeholder="{{ __('Name') }}*" readonly @disabled(!auth()->check())>
                                    </div>
                                    <div class="col-xl-6">
                                        <input name="email" type="email" value="{{ auth()->user()?->email }}"
                                            placeholder="{{ __('Email') }}*" readonly @disabled(!auth()->check())>
                                    </div>
                                    <div class="col-xl-12">
                                        <textarea name="comment" rows="5" placeholder="{{ __('Leave a reply') }}*" @disabled(!auth()->check())>{{ old('comment') }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <button class="common_btn mt_10" type="submit">{{ __('Post Comment') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInRight">
                    <div class="wsus__blog_sidebar wsus__sidebar">
                        <form class="wsus__sidebar_search" action="{{ route('website.blogs') }}" method="GET">
                            <input name="search" type="text" value="{{ old('search') }}" placeholder="Search Here...">
                            <button type="submit">
                                <i class="far fa-search"></i>
                            </button>
                        </form>
                        <div class="wsus__sidebar_recent_post">
                            <h3>{{ __('Recent Posts') }}</h3>
                            <ul class="d-flex flex-wrap">
                                @foreach ($recentPosts as $recentPost)
                                    <li>
                                        <a class="img" href="{{ route('website.blog', $recentPost->slug) }}">
                                            <img class="img-fluid"
                                                src="{{ $recentPost->image ? asset($recentPost->image) : asset($setting->default_image) }}"
                                                alt="{{ $recentPost->title }}">
                                        </a>
                                        <div class="text">
                                            <p>
                                                <i class="fal fa-calendar-alt"></i>
                                                {{ formattedDate($recentPost->created_at) }}
                                            </p>
                                            <a class="title" href="{{ route('website.blog', $recentPost->slug) }}">
                                                {{ htmlDecode($recentPost->title) }}
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="wsus__sidebar_blog_category">
                            <h3>{{ __('Categories') }}</h3>
                            <ul>
                                @foreach ($categories as $category)
                                    <li>
                                        <a
                                            href="{{ route('website.blogs', [
                                                'category' => $category->slug,
                                            ]) }}">{{ $category->title }}
                                            <span>({{ $category->posts_count ?? 0 }})</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="wsus__sidebar_blog_tags">
                            <h3>{{ __('Tags') }}</h3>
                            <ul class="d-flex flex-wrap">
                                @foreach ($allTags as $tag => $count)
                                    <li><a
                                            href="{{ route('website.blogs', [
                                                'tag' => $tag,
                                            ]) }}">
                                            {{ $tag }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        "use strict";
        $(document).ready(function() {
            $('.comment_btn').on('click', function(e) {
                e.preventDefault();

                if (AUTH_STATUS == 1) {
                    $('#commentForm').submit();
                } else {
                    $('#loginModal').modal('show');
                    return false;
                }
            });
        })
    </script>
@endpush
