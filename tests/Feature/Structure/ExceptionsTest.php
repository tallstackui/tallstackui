<?php

use TallStackUi\Components\Alert\Component as Alert;
use TallStackUi\Components\Icon\Component as Icon;
use TallStackUi\Components\Tooltip\Component as Tooltip;
use TallStackUi\Exceptions\InappropriateIconGuideExecution;
use TallStackUi\Exceptions\InvalidSelectedPositionException;
use TallStackUi\Exceptions\MissingLivewireException;

test('InappropriateIconGuideExecution extends Exception')
    ->expect(InappropriateIconGuideExecution::class)
    ->toExtend(Exception::class);

test('InappropriateIconGuideExecution validate returns null for Icon component', function () {
    expect(InappropriateIconGuideExecution::validate(Icon::class))->toBeNull();
});

test('InappropriateIconGuideExecution validate returns null for Tooltip component', function () {
    expect(InappropriateIconGuideExecution::validate(Tooltip::class))->toBeNull();
});

test('InappropriateIconGuideExecution validate throws for other components', function () {
    expect(fn () => InappropriateIconGuideExecution::validate(Alert::class))
        ->toThrow(InappropriateIconGuideExecution::class);
});

// InvalidSelectedPositionException

test('InvalidSelectedPositionException extends Exception')
    ->expect(InvalidSelectedPositionException::class)
    ->toExtend(Exception::class);

test('InvalidSelectedPositionException validate returns null for null position', function () {
    expect(InvalidSelectedPositionException::validate('Component'))->toBeNull();
});

test('InvalidSelectedPositionException validate returns null for valid positions', function (string $position) {
    expect(InvalidSelectedPositionException::validate('Component', $position))->toBeNull();
})->with([
    'bottom',
    'top-start',
    'left-end',
]);

test('InvalidSelectedPositionException validate throws for invalid position', function () {
    expect(fn () => InvalidSelectedPositionException::validate('Component', 'invalid-position'))
        ->toThrow(InvalidSelectedPositionException::class);
});

// MissingLivewireException

test('MissingLivewireException extends Exception')
    ->expect(MissingLivewireException::class)
    ->toExtend(Exception::class);

test('MissingLivewireException message includes component name', function () {
    expect(new MissingLivewireException('MyComponent'))
        ->getMessage()
        ->toContain('MyComponent');
});
