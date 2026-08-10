@foreach ($cartGroupBy as $name => $carts)
    <li class="d-flex justify-content-between">
        <h4>{{ $name }}</h4>
    </li>
    @foreach ($carts as $cart)
        <li>
            <a class="img" href="{{ route('website.product', $cart['slug']) }}">
                <img class="img-fluid w-100" src="{{ asset($cart['image']) }}" alt="{{ $cart['name'] }}">
            </a>
            <div class="text">
                <a
                    href="{{ route('website.product', $cart['slug']) }}">{{ $cart['name'] }}
                    <span>({{ $cart['sku'] }})</span>
                </a>
                <h6>{{ currency($cart['price']) }} x {{ $cart['qty'] }}</h6>
            </div>
        </li>
    @endforeach
@endforeach
