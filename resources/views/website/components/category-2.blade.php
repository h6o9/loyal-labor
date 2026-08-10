<a class="wsus__categories_2_item" href="{{ route('website.products', ['category' => $category->slug]) }}">
    <img class="img-fluid w-100" src="{{ asset($category->image) }}" alt="img">
    <div class="text">
        <p class="title">{{ $category->name }}</p>
        <span>{{ $category->products_count ?? 0 }} {{ __('Products') }}</span>
    </div>
</a>
