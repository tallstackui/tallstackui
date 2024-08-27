<?php

use TallStackUi\Foundation\Http\Controllers\TallStackUiAssetsController;

test('contains all methods')
    ->expect(TallStackUiAssetsController::class)
    ->toHaveMethod('scripts')
    ->toHaveMethod('styles');
