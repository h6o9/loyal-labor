<?php

use Illuminate\Support\Facades\Route;
use Modules\KnowYourClient\app\Http\Controllers\Admin\KycController;
use Modules\KnowYourClient\app\Http\Controllers\Admin\KycTypeController;
use Modules\KnowYourClient\app\Http\Controllers\Seller\SellerKycController;

Route::name('seller.kyc.')
    ->prefix('seller/kyc')
    ->middleware([
        'auth:web',
        'verified',
        'check.vendor',
    ])
    ->controller(SellerKycController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
    });

Route::name('admin.')
    ->prefix('admin')
    ->middleware([
        'auth:admin',
    ])
    ->group(function () {
        Route::resource('kyc', KycTypeController::class);

        Route::controller(KycController::class)->group(function () {
            Route::get('kyc-list', 'kycList')->name('kyc-list.index');
            Route::get('kyc-list/{id}', 'kycListShow')->name('kyc-list.show');
            Route::delete('delete-kyc-info/{id}', 'DestroyKyc')->name('kyc-list.delete-kyc-info');
            Route::put('update-kyc-status/{id}', 'UpdateKycStatus')->name('kyc-list.update-kyc-status');
        });
    });
