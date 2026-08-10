<a class="wsus__category_3_item {{ 'color_' . $loop->index + 1 }}"
    href="{{ route('website.products', ['category' => $category->slug]) }}">
    <div class="img">
        <img class="img-fluid w-100" src="{{ asset($category->image) }}" alt="category">
    </div>
    <div class="text">
        <b class="title">{{ $category->name }}</b>
        <p>{{ $category->products_count ?? 0 }} {{ __('Products') }}</p>
    </div>
</a>
