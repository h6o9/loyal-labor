    <!--============================
        MAIN MENU 3 START
    ==============================-->
    <section class="wsus__main_menu wsus__main_menu_3">
        <div class="container">
            <div class="row">
                <div class="col-xl-3">
                    <div class="wsus__menu_2_categories">
                        <a class="wsus__menu_2_browse" href="#">
                            <span>
                                <img class="img-fluid" src="{{ asset('website/images/brows_bar.webp') }}" alt="icon">
                                {{ __('Browse Categories') }}
                            </span>
                            <i class="fal fa-angle-down"></i>
                        </a>
                        <ul class="wsus__category_menu_2">
                            @foreach (allCategories(status: true, parentOnly: true) as $category)
                                <li>
                                    <a href="{{ route('website.products', ['category' => $category->slug]) }}">
                                        <span>
                                            @if (!is_null($category->icon))
                                                <img class="img-fluid w-100" src="{{ asset($category->icon) }}"
                                                    alt="icon">
                                            @endif
                                            <b>{{ $category->name }}</b>
                                        </span>
                                        <p>{{ $category->products_count ?? 0 }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-6">
                    @include('website.layouts.partials.main-menu.menu-bar')
                </div>
                <div class="col-xl-3">
                    <div class="wsus__menu_giftcard">
                        <a href="{{ route('website.gift.cards') }}">
                            <img class="img-fluid w-100" src="{{ asset('website/images/giftbox.webp') }}"
                                alt="icon">
                            {{ __('Gift Cards') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        MAIN MENU 3 END
    ==============================-->
