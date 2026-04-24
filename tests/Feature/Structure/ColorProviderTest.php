<?php

use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Components\Alert\Component as Alert;
use TallStackUi\Components\Avatar\Component as Avatar;
use TallStackUi\Components\BackToTop\Component as BackToTop;
use TallStackUi\Components\Badge\Component as Badge;
use TallStackUi\Components\Banner\Component as Banner;
use TallStackUi\Components\Boolean\Component as Boolean;
use TallStackUi\Components\Button\Circle\Component as Circle;
use TallStackUi\Components\Button\Normal\Component as Button;
use TallStackUi\Components\Card\Component as Card;
use TallStackUi\Components\Dial\Main\Component as Dial;
use TallStackUi\Components\Dialog\Component as Dialog;
use TallStackUi\Components\Environment\Component as Environment;
use TallStackUi\Components\Errors\Component as Errors;
use TallStackUi\Components\Form\Checkbox\Component as Checkbox;
use TallStackUi\Components\Form\Radio\Component as Radio;
use TallStackUi\Components\Form\Range\Component as Range;
use TallStackUi\Components\Form\Toggle\Component as Toggle;
use TallStackUi\Components\Link\Component as Link;
use TallStackUi\Components\Progress\Bar\Component as Progress;
use TallStackUi\Components\Progress\Circle\Component as ProgressCircle;
use TallStackUi\Components\Rating\Component as Rating;
use TallStackUi\Components\Stats\Component as Stats;
use TallStackUi\Components\Timeline\Items\Component as TimelineItems;
use TallStackUi\Components\Timeline\Main\Component as Timeline;
use TallStackUi\Components\Toast\Component as Toast;
use TallStackUi\Components\Tooltip\Component as Tooltip;
use TallStackUi\Console\SetupColorCommand;
use TallStackUi\Support\Colors\CompileColors;
use TallStackUi\Support\Colors\Concerns\SetupColors;

test('contains method')->expect(CompileColors::class)->toHaveMethod('of');

test('should use attribute', function (string $component) {
    expect($component)->toHaveAttribute(ColorsThroughOf::class);
})->with([
    Alert::class,
    BackToTop::class,
    Avatar::class,
    Button::class,
    Badge::class,
    Banner::class,
    Boolean::class,
    Card::class,
    Circle::class,
    Checkbox::class,
    Dial::class,
    Dialog::class,
    Environment::class,
    Errors::class,
    Link::class,
    Radio::class,
    Range::class,
    Rating::class,
    Progress::class,
    ProgressCircle::class,
    Stats::class,
    Timeline::class,
    TimelineItems::class,
    Toast::class,
    Tooltip::class,
    Toggle::class,
]);

test('attribute should only be used in the components', function () {
    expect(ColorsThroughOf::class)
        ->toOnlyBeUsedIn([
            SetupColorCommand::class,
            SetupColors::class,
            CompileColors::class,
            Alert::class,
            BackToTop::class,
            Avatar::class,
            Button::class,
            Badge::class,
            Banner::class,
            Boolean::class,
            Card::class,
            Circle::class,
            Checkbox::class,
            Dial::class,
            Dialog::class,
            Environment::class,
            Errors::class,
            Link::class,
            Radio::class,
            Range::class,
            Rating::class,
            Progress::class,
            ProgressCircle::class,
            Stats::class,
            Timeline::class,
            TimelineItems::class,
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
