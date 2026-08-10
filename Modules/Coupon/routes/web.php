<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupon\app\Http\Controllers\CouponController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {

    Route::resource('coupon', CouponController::class)->names('coupon');
    Route::put('/coupon/status-update/{id}', [CouponController::class, 'statusUpdate'])->name('coupon.status-update');

    Route::get('coupon-history', [CouponController::class, 'coupon_history'])->name('coupon-history');
});
