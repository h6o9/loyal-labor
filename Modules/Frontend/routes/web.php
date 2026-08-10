<?php

use Illuminate\Support\Facades\Route;
use Modules\Frontend\app\Http\Controllers\FrontendController;
use Modules\Frontend\app\Http\Controllers\ManageSectionController;

Route::name('admin.frontend.')
    ->middleware(['auth:admin', 'translation'])
    ->prefix('admin/frontend')
    ->controller(FrontendController::class)
    ->group(function () {
        Route::get('/', 'homepage')->name('homepage');
        Route::post('/update-homepage', 'updateHomepage')->name('homepage.update');
        Route::get('/manage', 'index')->name('index');
        Route::post('/{id}', 'update')->name('update');

        Route::get('/manage/sections/{section}', [ManageSectionController::class, 'index'])->name('section.index');
        Route::post('/manage/sections/{section}', [ManageSectionController::class, 'update'])->name('section.update');
    });
