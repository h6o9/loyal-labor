<div class="wsus__blog_item">
    <div class="img">
        <img class="img-fluid w-100" src="{{ asset($blog->image) }}" alt="blog">
    </div>
    <div class="wsus__blog_item_text">
        <ul>
            <li>{{ $blog->category->title ?? __('Uncategory') }}</li>
            <li>
                <span><img class="img-fluid w-100" src="{{ asset('website/images/calendar_3.webp') }}"
                        alt="icon"></span>
                {{ formattedDate($blog->created_at) }}
            </li>
        </ul>
        <a class="title" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a>
        <a class="link" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ __('Read More') }}<i
                class="far fa-angle-right"></i></a>
    </div>
</div>
