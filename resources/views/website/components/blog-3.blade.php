<div class="wsus__blog_3_item">
    <a class="img" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">
        <img class="img-fluid w-100" src="{{ asset($blog->image) }}" alt="blog">
    </a>
    <div class="text">
        <div class="text_top">
            <ul>
                <li>
                    <a href="javascript:void()">
                        <img class="img-fluid w-100" src="{{ asset('website/images/calendar.webp') }}" alt="icon">
                        {{ formattedDate($blog->created_at) }}
                    </a>
                </li>
                <li>
                    <a href="javascript:void()">
                        <img class="img-fluid w-100" src="{{ asset('website/images/badgh.webp') }}" alt="icon">
                        {{ $blog->category->title ?? __('Uncategory') }}
                    </a>
                </li>
            </ul>
            <a class="title" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a>
        </div>
        <div class="text_bottom">
            <a href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ __('Read More') }}<i
                    class="far fa-arrow-right" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
