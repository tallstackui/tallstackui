<?php

use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Support\Colors\CompileColors;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Banner;
use TallStackUi\View\Components\Boolean;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Range;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Link;
use TallStackUi\View\Components\Progress\Circle as ProgressCircle;
use TallStackUi\View\Components\Progress\Progress;
use TallStackUi\View\Components\Rating;
use TallStackUi\View\Components\Stats;
use TallStackUi\View\Components\Tooltip;

test('contains method')->expect(CompileColors::class)->toHaveMethod('of');

test('should use attribute', function (string $component) {
    expect($component)->toHaveAttribute(ColorsThroughOf::class);
})->with([
    Alert::class,
    Avatar::class,
    Button::class,
    Badge::class,
    Banner::class,
    Boolean::class,
    Circle::class,
    Checkbox::class,
    Dialog::class,
    Errors::class,
    Link::class,
    Radio::class,
    Range::class,
    Rating::class,
    Progress::class,
    ProgressCircle::class,
    Stats::class,
    Toast::class,
    Tooltip::class,
    Toggle::class,
]);

test('attribute should only be used in the components', function () {
    expect(ColorsThroughOf::class)
        ->toOnlyBeUsedIn([
            CompileColors::class,
            Alert::class,
            Avatar::class,
            Button::class,
            Badge::class,
            Banner::class,
            Boolean::class,
            Circle::class,
            Checkbox::class,
            Dialog::class,
            Errors::class,
            Link::class,
            Radio::class,
            Range::class,
            Rating::class,
            Progress::class,
            ProgressCircle::class,
            Stats::class,
            Toast::class,
            Tooltip::class,
            Toggle::class,
        ]);
});

test('should return a correct array', function () {
    $colors = CompileColors::of(new Alert);

    expect($colors)
        ->toBeArray()
        ->and($colors)
        ->toBe(['background' => 'bg-primary-600', 'text' => 'text-primary-50']);
});
