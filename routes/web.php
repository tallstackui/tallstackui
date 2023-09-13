<?php

use Illuminate\Support\Facades\Route;
use TasteUi\Http\Controllers\TasteUiAssetsController;

Route::name('tasteui.')
    ->prefix('/tasteui')
    ->controller(TasteUiAssetsController::class)
    ->group(function () {
        Route::get('/scripts', 'scripts')->name('scripts');
        Route::get('/styles', 'styles')->name('styles');
    });
