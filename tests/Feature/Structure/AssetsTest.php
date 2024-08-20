<?php

use TallStackUi\Http\Controllers\TallStackUiAssetsController;

test('contains all methods')
    ->expect(TallStackUiAssetsController::class)
    ->toHaveMethod('scripts')
    ->toHaveMethod('styles');
