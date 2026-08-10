<?php

use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerProductGalleryController;
use App\Http\Controllers\Seller\SellerProductPriceController;
use App\Http\Controllers\Seller\SellerProductReviewController;
use App\Http\Controllers\Seller\SellerProductStockController;
use App\Http\Controllers\Seller\SellerProductTosController;
use App\Http\Controllers\Seller\SellerProductVariantController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\Seller\WithdrawController;
use App\Http\Middleware\VendorKycVerifiedMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'maintenance.mode'])->group(function () {
    Route::get('dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('all-notifications', [SellerDashboardController::class, 'allNotifications'])->name('all-notifications');

    Route::controller(SellerProfileController::class)->group(function () {
        Route::get('my-profile', 'index')->name('my-profile');
        Route::put('update-seller-profile', [SellerProfileController::class, 'updateSellerProfile'])->name('update-seller-profile');
        Route::get('change-password', [SellerProfileController::class, 'changePassword'])->name('change-password');
        Route::put('password-update', [SellerProfileController::class, 'updatePassword'])->name('password-update');
        Route::get('shop-profile', [SellerProfileController::class, 'myShop'])->name('shop-profile');
        Route::put('update-seller-shop', [SellerProfileController::class, 'updateSellerSop'])->name('update-seller-shop');
    });

    Route::middleware(VendorKycVerifiedMiddleware::class)->group(function () {
        Route::get('product/search', [SellerProductController::class, 'search'])->name('product.search');
        Route::post('product/status/{id}', [SellerProductController::class, 'status'])->name('product.status');
        Route::get('product/view/{id}', [SellerProductController::class, 'singleProduct'])->name('product.view');
        Route::get('product/get/{id}', [SellerProductController::class, 'getProductJson'])->name('product.json');
        Route::resource('product', SellerProductController::class);

        Route::get('product/product-gallery/{id}', [SellerProductGalleryController::class, 'productGallery'])->name('product-gallery');
        Route::post('product/product-gallery/{id}', [SellerProductGalleryController::class, 'productGalleryStore'])->name('product-gallery.store');
        Route::delete('product/product-gallery/{id}', [SellerProductGalleryController::class, 'productGalleryDelete'])->name('product-gallery.delete');

        Route::get('product/related-product/{id}', [SellerProductController::class, 'related_product'])->name('related-products');
        Route::post('product/related-product/{id}', [SellerProductController::class, 'related_product_store'])->name('store-related-products');

        Route::get('product/related-variant/{id}', [SellerProductVariantController::class, 'product_variant'])->name('product.product-variant');
        Route::get('product/related-variant/{id}/create', [SellerProductVariantController::class, 'product_variant_create'])->name('product.product-variant.create');
        Route::post('product/related-variant/{id}', [SellerProductVariantController::class, 'product_variant_store'])->name('product.product-variant.store');
        Route::get('product/related-variant/edit/{variant_id}', [SellerProductVariantController::class, 'product_variant_edit'])->name('product.product-variant.edit');
        Route::put('product/related-variant/{variant_id}', [SellerProductVariantController::class, 'product_variant_update'])->name('product.product-variant.update');

        Route::delete('product/related-variant/{variant_id}', [SellerProductVariantController::class, 'product_variant_delete'])->name('product.product-variant.delete');
        Route::delete('product/related-variant-image/{image_id}', [SellerProductVariantController::class, 'product_variant_image_delete'])->name('product.product-variant-image.delete');

        Route::get('/product-prices', [SellerProductPriceController::class, 'priceUpdate'])->name('products.product-prices');
        Route::post('/update-product-price', [SellerProductPriceController::class, 'priceUpdateStore'])->name('products.price-update.store');

        Route::get('/product-inventory', [SellerProductStockController::class, 'productInventory'])->name('products.product-inventory');
        Route::post('/update-product-inventory', [SellerProductStockController::class, 'productInventoryStore'])->name('products.product-inventory.store');

        Route::get('/product-return-policy', [SellerProductTosController::class, 'productReturnPolicy'])->name('products.product-return-policy');
        Route::delete('/product-return-policy/{id}', [SellerProductTosController::class, 'productReturnPolicyDelete'])->name('products.product-return-policy.delete');
        Route::post('attribute/get-value/', [SellerProductVariantController::class, 'getAttributeValue'])->name('attribute.get.value');

        Route::get('product-review', [SellerProductReviewController::class, 'index'])->name('product-review');
        Route::get('show-product-review/{id}', [SellerProductReviewController::class, 'show'])->name('show-product-review');

        Route::resource('my-withdraw', WithdrawController::class);
        Route::get('get-withdraw-account-info/{id}', [WithdrawController::class, 'getWithDrawAccountInfo'])->name('get-withdraw-account-info');

        Route::controller(SellerOrderController::class)->group(function () {
            Route::get('/orders', 'index')->name('orders.index');
            Route::get('/orders/pending-orders', 'pending_order')->name('orders.pending');
            Route::get('/orders/order/{id}', 'show')->name('orders.show');
            Route::post('/orders/status-update/{id}', 'updateOrderStatus')->name('orders.status.update');
            Route::get('/orders/order-invoice/{id}', 'invoice')->name('orders.invoice');
        });
    });
});
