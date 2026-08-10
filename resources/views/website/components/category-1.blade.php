<a class="wsus__category_item {{ 'color_' . $loop->index + 1 }}"
    href="{{ route('website.products', ['category' => $category->slug]) }}">
    <img class="img-fluid w-100" src="{{ asset($category->image) }}" alt="category">
    <div class="text">
        <p class="title">{{ $category->name }}</p>
        <span>{{ $category->products_count ?? 0 }} {{ __('Products') }}</span>
    </div>
</a>
