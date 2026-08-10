<div class="wsus__blog_4_item">
    <a class="img" href="#">
        <img class="img-fluid w-100" src="{{ asset($blog->image) }}" alt="blog">
    </a>
    <div class="wsus__blog_4_item_text">
        <ul>
            <li>
                <img class="img-fluid w-100" src="{{ asset('website/images/calendar_2.webp') }}" alt="icon">
                {{ formattedDate($blog->created_at) }}
            </li>
            <li>
                {{ $blog->category->title ?? __('Uncategory') }}
            </li>
        </ul>
        <a class="title" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a>

        <a class="common_btn border_btn"
            href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ __('Read More') }}<i
                class="far fa-arrow-right" aria-hidden="true"></i></a>
    </div>
</div>
