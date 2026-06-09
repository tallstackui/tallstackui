<?php

use Symfony\Component\HttpKernel\Exception\HttpException;
use TallStackUi\Http\Controllers\TallStackUiAssetsController;

test('contains all methods')
    ->expect(TallStackUiAssetsController::class)
    ->toHaveMethod('script')
    ->toHaveMethod('style');

test('aborts with 404 when the requested script file does not exist', function () {
    $controller = new TallStackUiAssetsController;

    try {
        $controller->script('non-existent-chunk-'.uniqid().'.js.map');
        $this->fail('Expected HttpException was not thrown.');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(404);
    }
});

test('aborts with 404 when the requested style file does not exist', function () {
    $controller = new TallStackUiAssetsController;

    try {
        $controller->style('non-existent-chunk-'.uniqid().'.css');
        $this->fail('Expected HttpException was not thrown.');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(404);
    }
});
