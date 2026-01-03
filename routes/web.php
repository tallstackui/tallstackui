<?php

use Illuminate\Support\Facades\Route;
use TallStackUi\Http\Controllers\TallStackUiAssetsController;

Route::name('tallstackui.')
    ->prefix('/tallstackui')
    ->group(function () {
        Route::get('/script/{file?}', [TallStackUiAssetsController::class, 'script'])->name('script');
        Route::get('/style/{file?}', [TallStackUiAssetsController::class, 'style'])->name('style');
    });
