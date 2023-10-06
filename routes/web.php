<?php

use Illuminate\Support\Facades\Route;
use TallStackUi\Http\Controllers\TallStackUiAssetsController;

Route::name('tasteui.')
    ->prefix('/tasteui')
    ->controller(TallStackUiAssetsController::class)
    ->group(function () {
        Route::get('/scripts', 'scripts')->name('scripts');
        Route::get('/styles', 'styles')->name('styles');
    });
