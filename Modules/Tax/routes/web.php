<?php

use Illuminate\Support\Facades\Route;
use Modules\Tax\app\Http\Controllers\TaxController;

Route::middleware(['auth:admin', 'translation'])->name('admin.')->prefix('admin')->group(function () {
    Route::resource('tax', TaxController::class)->names('tax')->except('show');
    Route::put('/tax/status-update/{id}', [TaxController::class, 'statusUpdate'])->name('tax.status-update');
});
