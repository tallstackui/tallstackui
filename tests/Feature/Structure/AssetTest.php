<?php

use TallStackUi\Http\Controllers\TallStackUiAssetsController;

describe('TallStackUiAssetsController tests', function () {
    test('contains all methods', function () {
        expect(TallStackUiAssetsController::class)
            ->toHaveMethod('scripts')
            ->toHaveMethod('styles');
    });
});
