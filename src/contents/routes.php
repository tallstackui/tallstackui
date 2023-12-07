<?php

use Illuminate\Support\Facades\Route;
use TallStackUi\Http\Controllers\TallStackUiAssetsController;

Route::name('tallstackui.')
    ->prefix('/tallstackui')
    ->controller(TallStackUiAssetsController::class)
    ->group(function () {
        Route::get('/script/{file?}', 'script')->name('script');
        Route::get('/style/{file?}', 'style')->name('style');
    });
