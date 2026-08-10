<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\app\Http\Controllers\Admin\WalletController as AdminWalletController;

// Route::group(['as' => 'wallet.', 'prefix' => 'wallet', 'middleware' => ['auth:web', 'translation']], function () {

// });

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::controller(AdminWalletController::class)->group(function () {
        Route::get('/wallet-history', 'index')->name('wallet-history');
        Route::get('/pending-wallet-payment', 'pending_wallet_payment')->name('pending-wallet-payment');
        Route::get('/rejected-wallet-payment', 'rejected_wallet_payment')->name('rejected-wallet-payment');
        Route::get('/show-wallet-history/{id}', 'show')->name('show-wallet-history');
        Route::delete('/delete-wallet-history/{id}', 'destroy')->name('delete-wallet-history');
        Route::post('/rejected-wallet-request/{id}', 'rejected_wallet_request')->name('rejected-wallet-request');
        Route::post('/approved-wallet-request/{id}', 'approved_wallet_request')->name('approved-wallet-request');
        Route::post('/update-auto-approve-status/{id}', 'autoApproveStatus')->name('wallet-auto-approve-status');
    });
});
