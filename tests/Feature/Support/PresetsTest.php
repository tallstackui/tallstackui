<?php

use TallStackUi\Components\Banner\Component as Banner;
use TallStackUi\Components\Card\Component as Card;
use TallStackUi\Components\Carousel\Component as Carousel;
use TallStackUi\Components\Dialog\Component as Dialog;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Components\Modal\Component as Modal;
use TallStackUi\Components\Slide\Component as Slide;
use TallStackUi\Customization\Presets;
use TallStackUi\Facades\TallStackUi;

afterEach(function () {
    Presets::reset();
});

it('can access presets from customization', function () {
    expect(TallStackUi::customize()->presets())->toBeInstanceOf(Presets::class);
});

it('can chain flash preset', function () {
    $presets = TallStackUi::customize()->presets();

    expect($presets->flash())->toBeInstanceOf(Presets::class);
});

it('returns false when no preset is active', function () {
    expect(Presets::is('flash', Modal::class))->toBeFalse();
});

it('can activate flash for all components', function () {
    TallStackUi::customize()->presets()->flash();

    expect(Presets::is('flash', Modal::class))->toBeTrue()
        ->and(Presets::is('flash', Slide::class))->toBeTrue()
        ->and(Presets::is('flash', Dialog::class))->toBeTrue()
        ->and(Presets::is('flash', Card::class))->toBeTrue()
        ->and(Presets::is('flash', Banner::class))->toBeTrue()
        ->and(Presets::is('flash', Floating::class))->toBeTrue()
        ->and(Presets::is('flash', Carousel::class))->toBeTrue();
});

it('can activate flash for specific component', function () {
    TallStackUi::customize()->presets()->flash(Modal::class);

    expect(Presets::is('flash', Modal::class))->toBeTrue()
        ->and(Presets::is('flash', Slide::class))->toBeFalse()
        ->and(Presets::is('flash', Card::class))->toBeFalse();
});

it('can activate flash for multiple specific components', function () {
    TallStackUi::customize()->presets()->flash(Modal::class, Slide::class);

    expect(Presets::is('flash', Modal::class))->toBeTrue()
        ->and(Presets::is('flash', Slide::class))->toBeTrue()
        ->and(Presets::is('flash', Card::class))->toBeFalse();
});

it('can reset presets', function () {
    TallStackUi::customize()->presets()->flash();

    expect(Presets::is('flash', Modal::class))->toBeTrue();

    Presets::reset();

    expect(Presets::is('flash', Modal::class))->toBeFalse();
});

it('can check unknown preset without errors', function () {
    expect(Presets::is('unknown-preset', Modal::class))->toBeFalse();
});

it('can remove transitions from modal when flash is global', function () {
    $component = <<<'HTML'
    <x-modal title="Foo">Bar</x-modal>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->presets()->flash();

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from modal when targeted', function () {
    $component = <<<'HTML'
    <x-modal title="Foo">Bar</x-modal>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->presets()->flash(Modal::class);

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from slide when flash is global', function () {
    $component = <<<'HTML'
    <x-slide title="Foo">Bar</x-slide>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->presets()->flash();

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from card when flash is global', function () {
    $component = <<<'HTML'
    <x-card header="Foo" minimize>Bar</x-card>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->presets()->flash();

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from carousel when flash is global', function () {
    $component = <<<'HTML'
    <x-carousel :images="[['image' => 'foo.jpg']]" />
    HTML;

    expect($component)->render()->toContain('x-transition.opacity');

    TallStackUi::customize()->presets()->flash();

    expect($component)->render()->not->toContain('x-transition.opacity');
});

it('does not affect untargeted components', function () {
    TallStackUi::customize()->presets()->flash(Modal::class);

    $card = <<<'HTML'
    <x-card header="Foo" minimize>Bar</x-card>
    HTML;

    expect($card)->render()->toContain('x-transition:enter');
});

it('can remove transitions from floating when flash is global', function () {
    TallStackUi::customize()->presets()->flash();

    $component = <<<'HTML'
    <x-floating>Content</x-floating>
    HTML;

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can use helper function with class string', function () {
    TallStackUi::customize()->presets()->flash(Modal::class);

    expect(__ts_preset('flash', Modal::class))->toBeTrue()
        ->and(__ts_preset('flash', Slide::class))->toBeFalse();
});
