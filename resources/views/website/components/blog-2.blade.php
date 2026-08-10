<div class="wsus__blog_2_item">
    <a class="img" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">
        <img class="img-fluid w-100" src="{{ asset($blog->image) }}" alt="blog">
        <span>{{ formattedDate($blog->created_at) }}</span>
    </a>
    <div class="wsus__blog_2_item_text">
        <ul>
            <li>
                <span>
                    <img class="img-fluid w-100" src="{{ asset('website/images/badgh.webp') }}" alt="icon">
                </span>
                {{ $blog->category->title ?? __('Uncategory') }}
            </li>
        </ul>
        <a class="title" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a>
        <a class="link" href="{{ route('website.blog', ['slug' => $blog->slug]) }}">{{ __('Read More') }}<i
                class="far fa-arrow-right" aria-hidden="true"></i></a>
    </div>
</div>
