<a class="category_item_4" href="{{ route('website.products', ['category' => $category->slug]) }}">
    <span>
        <img class="img-fluid w-100" src="{{ asset($category->icon) }}" alt="icon">
    </span>
    {{ $category->name }}
</a>
