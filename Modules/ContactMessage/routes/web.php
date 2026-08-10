<?php

use Illuminate\Support\Facades\Route;
use Modules\ContactMessage\app\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use Modules\ContactMessage\app\Http\Controllers\ContactMessageController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::get('contact-messages', [AdminContactMessageController::class, 'index'])->name('contact-messages');
    Route::get('contact-message/{id}', [AdminContactMessageController::class, 'show'])->name('contact-message');
    Route::delete('contact-message-delete/{id}', [AdminContactMessageController::class, 'destroy'])->name('contact-message-delete');
});

Route::post('send-contact-message', [ContactMessageController::class, 'store'])->name('website.send-contact-message')->middleware('throttle:3,60');
