<?php

use TallStackUi\Http\Controllers\TallStackUiAssetsController;

describe('TallStackUiAssetsController', function () {
    test('contains all methods')
        ->expect(TallStackUiAssetsController::class)
        ->toHaveMethod('scripts')
        ->toHaveMethod('styles');
});
