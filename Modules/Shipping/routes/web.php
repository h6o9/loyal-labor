<?php

use Illuminate\Support\Facades\Route;
use Modules\Shipping\app\Http\Controllers\ShippingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::post('shipping/settings/update', [ShippingController::class, 'updateSetting'])->name('shipping.setting.update');
    Route::resource('shipping', ShippingController::class)->names('shipping');
});
