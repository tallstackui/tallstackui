<?php

use Illuminate\Support\Facades\Route;
use TallStackUi\Http\Controllers\TallStackUiAssetsController;

Route::name('tallstackui.')
    ->prefix('/tallstackui')
    ->controller(TallStackUiAssetsController::class)
    ->group(function () {
        Route::get('/script', 'script')->name('script');
        Route::get('/style', 'style')->name('style');
    });
