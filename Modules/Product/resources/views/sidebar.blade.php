@if (checkAdminHasPermission('product.view') ||
        checkAdminHasPermission('product.category.view') ||
        checkAdminHasPermission('product.brand.view') ||
        checkAdminHasPermission('product.barcode.print') ||
        checkAdminHasPermission('product.create') ||
        checkAdminHasPermission('product.edit') ||
        checkAdminHasPermission('product.attribute.view') ||
        checkAdminHasPermission('product.label.view') ||
        checkAdminHasPermission('product.unit.view') ||
        checkAdminHasPermission('product.tags.view') ||
        checkAdminHasPermission('product.seller.view'))
    @php
        $pendingReviews = pendingProductReviews();
        $pendingProducts = pendingProducts();
    @endphp
    <li
        class="nav-item dropdown {{ isRoute(['admin.product.*', 'admin.unit.*', 'admin.category.*', 'admin.brand.*', 'admin.attribute.*', 'admin.attributes.*', 'admin.label.*', 'admin.product.tags.*', 'admin.products.product-prices', 'admin.products.product-inventory', 'admin.products.product-return-policy', 'admin.product-review', 'admin.product-gallery*', 'admin.show-product-review'], 'active') }}">
        <a class="nav-link has-dropdown" href="javascript:void()"><i
                class="fas fa-newspaper"></i><span
                class="{{ $pendingProducts > 0 || $pendingReviews > 0 ? 'beep-warning' : '' }}">{{ __('Manage Product') }}</span></a>

        <ul class="dropdown-menu">
            @adminCan('product.view')
                <li class="{{ isRoute(['admin.product.*', 'admin.product-gallery*'], 'active') }}">
                    <a class="nav-link" href="{{ route('admin.product.index') }}">
                        {{ __('Product List') }}
                    </a>
                </li>

                <li class="{{ isRoute(['admin.products.product-prices*'], 'active') }}">
                    <a class="nav-link" href="{{ route('admin.products.product-prices') }}">
                        {{ __('Product Prices') }}
                    </a>
                </li>

                <li class="{{ isRoute(['admin.products.product-inventory*'], 'active') }}">
                    <a class="nav-link" href="{{ route('admin.products.product-inventory') }}">
                        {{ __('Product Inventory') }}
                    </a>
                </li>
            @endadminCan

            @adminCan('product.reviews.view')
                <li class="{{ isRoute(['admin.product-review', 'admin.show-product-review'], 'active') }}">
                    <a class="nav-link {{ $pendingReviews > 0 ? 'beep beep-sidebar' : '' }}"
                        href="{{ route('admin.product-review') }}">
                        {{ __('Product Reviews') }} {{ $pendingReviews > 0 ? '(' . $pendingReviews . ')' : '' }}
                    </a>
                </li>
            @endadminCan

            @adminCan('product.category.view')
                <li class="{{ isRoute(['admin.category.*'], 'active') }}">
                    <a class="nav-link" href="{{ route('admin.category.index') }}">
                        {{ __('Category List') }}
                    </a>
                </li>
            @endadminCan
            @adminCan('product.brand.view')
                <li class="{{ isRoute(['admin.brand.*'], 'active') }}">
                    <a class="nav-link" href="{{ route('admin.brand.index') }}">
                        {{ __('Brand List') }}
                    </a>
                </li>
            @endadminCan

            @adminCan('product.attribute.view')
                <li class="{{ Route::is(['admin.attribute.*', 'admin.attributes.*']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.attribute.index') }}">
                        {{ __('Attribute') }}
                    </a>
                </li>
            @endadminCan

            @adminCan('product.label.view')
                <li class="{{ Route::is('admin.label.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.label.index') }}">
                        {{ __('Labels') }}
                    </a>
                </li>
            @endadminCan

            @adminCan('product.unit.view')
                <li class="{{ Route::is('admin.unit.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.unit.index') }}">
                        {{ __('Unit Types') }}
                    </a>
                </li>
            @endadminCan

            @adminCan('product.tags.view')
                <li class="{{ isRoute(['admin.product.tags*'], 'active') }}">
                    <a class="nav-link" href="{{ route('admin.product.tags.index') }}">
                        {{ __('Product Tags') }}
                    </a>
                </li>
            @endadminCan
        </ul>
    </li>

    @adminCan('product.seller.view')
        <li class="{{ isRoute(['admin.seller.products.index'], 'active') }}">
            <a class="nav-link {{ $pendingAvailable = $pendingProducts > 0 ? 'beep beep-sidebar' : '' }}"
                href="{{ route('admin.seller.products.index') }}"><i class="fas fa-cube"></i>
                <span>{{ __('Seller Products') }}
                    <span
                        class="text-warning">{{ $pendingAvailable ? '(' . $pendingProducts . ')' : '' }}</span></span>
            </a>
        </li>
    @endadminCan

@endif
