<?php

use Illuminate\Support\Facades\Route;
use TallStackUi\Http\Controllers\TallStackUiAssetsController;
use TallStackUi\Http\Controllers\TallStackUiCommandPaletteController;

Route::name('tallstackui.')
    ->prefix('/tallstackui')
    ->group(function () {
        Route::get('/script/{file?}', [TallStackUiAssetsController::class, 'script'])->name('script');
        Route::get('/style/{file?}', [TallStackUiAssetsController::class, 'style'])->name('style');
        Route::post('/command-palette/action', TallStackUiCommandPaletteController::class)->name('command-palette.action')->middleware('web');
    });
