<div class="row align-items-center">
    <div class="col-md-4 col-xl-5">
        <div class="wsus__modal_img">
            <img class="img-fluid w-100" src="{{ asset($product->thumbnail_image) }}" alt="{{ $product->name }}"
                loading="lazy">
        </div>
    </div>
    <div class="col-md-8 col-xl-7">
        <div class="wsus__modal_text">
            <a class="title" href="{{ route('website.product', $product->slug) }}">{{ $product->name }}</a>
            @include('components::product-details', [
                'product' => $product,
            ])
        </div>
    </div>
</div>
