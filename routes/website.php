<?php

use App\Http\Controllers\Auth\VendorRegisteredController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GuestCheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('clear/cache', function () {
    Artisan::call('optimize:clear');
});

Route::middleware(['translation', 'maintenance.mode'])->group(
    function () {
        Route::name('website.')->controller(HomeController::class)->group(function () {
            // Route::get('/', 'index')->name('home');
            Route::get('/categories', 'categories')->name('categories');
            Route::get('/products', 'products')->name('products');
            Route::get('/products/{product:slug}', 'product')->name('product');
            Route::get('/product-modal', 'productModal')->name('product-modal');
            Route::get('/flash-deals', 'flashDeals')->name('flash.deals');
            Route::get('/brands', 'brands')->name('brands');
            Route::get('/shops', 'shops')->name('shops');
            Route::get('/gift-cards', 'giftCards')->name('gift.cards');
            Route::get('/shops/{slug}', 'shop')->name('shop');

            Route::get('/about-us', 'aboutUs')->name('about.us');
            Route::get('/contact-us', 'contactUs')->name('contact.us');
            Route::get('/faq', 'faq')->name('faq');
            Route::get('/privacy-policy', 'privacyPolicy')->name('privacy.policy');
            Route::get('/return-policy', 'returnPolicy')->name('return.policy');
            Route::get('/terms-and-conditions', 'termsAndConditions')->name('terms.and.conditions');
            Route::get('/pages/{slug}', 'customPage')->name('custom.page');
            Route::get('/track-order/{orderId?}/{email?}', 'trackOrder')->name('track.order');
            Route::get('/blogs', 'blogs')->name('blogs');
            Route::get('/blogs/{slug}', 'blog')->name('blog');
            Route::post('/blogs/comment/{slug}', 'blogCommentStore')->name('blog.comment.store');

            Route::get('/invoice/{uuid}', [UserController::class, 'invoice'])->name('invoice')->withoutMiddleware('auth:web');
            Route::get('/complete-payment/{uuid}/{type?}', [UserController::class, 'completePayment'])->name('complete.payment')->withoutMiddleware('auth:web');
            Route::get('/payment-success/{token}', 'paymentApiSuccess')->name('payment-api.webview-success-payment');
            Route::get('/payment-failed', 'paymentApiFailed')->name('payment-api.webview-failed-payment');

            Route::get('/all-states-by-country/{id}', [StateController::class, 'getAllStateByCountry'])->name('get.all.states.by.country');
            Route::get('/all-cities-by-state/{id}', [CityController::class, 'getAllCitiesByState'])->name('get.all.cities.by.state');

            Route::get('join-as-seller', [VendorRegisteredController::class, 'joinAsSeller'])->name('join-as-seller');
            Route::post('join-as-seller', [VendorRegisteredController::class, 'storeSellerInfo'])->name('join-as-seller.store');
            Route::get('verify-shop/{token}', [VendorRegisteredController::class, 'verifyShop'])->name('verify.shop');

            Route::post('/get-guest-checkout-summary', [GuestCheckoutController::class, 'getCheckoutSummary'])->name('get.guest.checkout.summary');
            Route::get('/order-completed/{uuid}', [GuestCheckoutController::class, 'showCompleteOrder'])->name('guest.order.complete');
            Route::get('/generated-invoice/{orderId}', [GuestCheckoutController::class, 'showInvoice'])->name('guest.invoice');

            // cart
            Route::controller(CartController::class)->group(function () {
                Route::get('/cart', 'cart')->name('cart');
                Route::get('cart/resync', 'resyncTotalCart')->name('cart.resync');
                Route::post('/cart', 'cartStore')->name('cart.store');
                Route::get('/cart/remove/{id}', 'cartRemove')->name('cart.remove');
                Route::post('/cart/update', 'cartUpdate')->name('cart.update');
                Route::get('/apply-coupon', 'applyCoupon')->name('apply.coupon');
                Route::post('/get-checkout-summary', 'getCheckoutSummary')->name('get.checkout.summary');
            });

            Route::controller(CheckoutController::class)->group(function () {
                Route::get('/checkout', 'checkout')->name('checkout');
                Route::post('/get-checkout-summary', 'getCheckoutSummary')->name('get.checkout.summary');
                Route::post('/place-order', 'placeOrder')->name('place.order');
            });

            Route::group(['middleware' => 'auth'], function () {
                Route::get('/compare', [CartController::class, 'compare'])->name('compare');

                Route::controller(WishlistController::class)->group(function () {
                    Route::get('/wishlist', 'wishlist')->name('wishlist');
                    Route::post('wishlist/store', 'wishlistStore')->name('wishlist.store');
                    Route::get('wishlist/remove/{slug}', 'wishlistRemove')->name('wishlist.remove');
                });

                Route::name('user.')->prefix('user')->controller(UserController::class)->group(function () {
                    Route::get('dashboard', 'dashboard')->name('dashboard');

                    Route::get('edit-profile', 'editProfile')->name('edit.profile');
                    Route::put('edit-profile', 'storeProfile')->name('store.profile');
                    Route::delete('edit-profile', 'deleteProfile')->name('delete.profile');
                    Route::get('change-password', 'changePassword')->name('change.password');
                    Route::post('change-password', 'updatePassword')->name('update.password');

                    Route::get('reviews', 'reviews')->name('reviews');
                    Route::get('add-reviews/{order_id}/{product_id?}', 'addReviews')->name('reviews.add');
                    Route::post('store-reviews', 'storeReviews')->name('reviews.store');
                    Route::delete('delete-reviews/{id}', 'deleteReviews')->name('reviews.delete');

                    Route::get('/address', 'address')->name('address.index');
                    Route::post('/address', 'storeAddress')->name('address.store');
                    Route::delete('/address-delete/{id}', 'deleteAddress')->name('address.delete');

                    Route::get('/orders', 'orders')->name('orders');
                    Route::post('/orders-cancel/{uuid}', 'cancelOrder')->name('orders.cancel');
                    Route::get('/wishlist', 'wishlist')->name('wishlist');
                    Route::get('/invoice/{uuid}', 'invoice')->name('invoice');
                });
            });
        });
    }
);
