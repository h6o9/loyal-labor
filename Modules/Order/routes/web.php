<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\app\Http\Controllers\OrderController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders');
        Route::get('/pending-orders', 'pending_order')->name('pending-orders');
        Route::get('/pending-payment', 'pending_payment')->name('pending-payment');
        Route::get('/rejected-payment', 'rejected_payment')->name('rejected-payment');
        Route::get('/orders/order-invoice/{id}', 'invoice')->name('orders.invoice');
        Route::get('/order/{id}', 'show')->name('order');
        Route::get('/orders/all-transactions/{id?}', 'allTransactionHistories')->name('orders.all-transactions');
        Route::get('/orders/all-status-updates/{id?}', 'statusUpdateHistories')->name('orders.all-status-updates');
        Route::post('/orders/status-update/{id}', 'updateOrderStatus')->name('orders.status.update');
        Route::post('/orders/payment-status-update/{id}', 'updateOrderPaymentStatus')->name('orders.payment.status.update');
        Route::post('/orders/bank-payment-accept/{id}', 'acceptBankPayment')->name('orders.bank-payment.accept');
        Route::delete('/order-delete/{id}', 'destroy')->name('order-delete');
    });
});
