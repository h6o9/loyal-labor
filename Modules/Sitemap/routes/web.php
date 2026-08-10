<?php

use Illuminate\Support\Facades\Route;
use Modules\Sitemap\app\Http\Controllers\SitemapController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('sitemap', SitemapController::class)->names('sitemap')->only(['index', 'store']);
});
