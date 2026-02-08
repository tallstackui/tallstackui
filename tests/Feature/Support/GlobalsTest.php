<?php

use TallStackUi\Components\Banner\Component as Banner;
use TallStackUi\Components\Card\Component as Card;
use TallStackUi\Components\Carousel\Component as Carousel;
use TallStackUi\Components\Dialog\Component as Dialog;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Components\Modal\Component as Modal;
use TallStackUi\Components\Slide\Component as Slide;
use TallStackUi\Customization\Globals;
use TallStackUi\Facades\TallStackUi;

afterEach(function () {
    Globals::reset();
});

it('can access globals from customization', function () {
    expect(TallStackUi::customize()->globals())->toBeInstanceOf(Globals::class);
});

it('can chain flash global', function () {
    $globals = TallStackUi::customize()->globals();

    expect($globals->flash())->toBeInstanceOf(Globals::class);
});

it('returns false when no global is active', function () {
    expect(Globals::is('flash', Modal::class))->toBeFalse();
});

it('can activate flash for all components', function () {
    TallStackUi::customize()->globals()->flash();

    expect(Globals::is('flash', Modal::class))->toBeTrue()
        ->and(Globals::is('flash', Slide::class))->toBeTrue()
        ->and(Globals::is('flash', Dialog::class))->toBeTrue()
        ->and(Globals::is('flash', Card::class))->toBeTrue()
        ->and(Globals::is('flash', Banner::class))->toBeTrue()
        ->and(Globals::is('flash', Floating::class))->toBeTrue()
        ->and(Globals::is('flash', Carousel::class))->toBeTrue();
});

it('can activate flash for specific component', function () {
    TallStackUi::customize()->globals()->flash(only: [Modal::class]);

    expect(Globals::is('flash', Modal::class))->toBeTrue()
        ->and(Globals::is('flash', Slide::class))->toBeFalse()
        ->and(Globals::is('flash', Card::class))->toBeFalse();
});

it('can activate flash for multiple specific components', function () {
    TallStackUi::customize()->globals()->flash(only: [Modal::class, Slide::class]);

    expect(Globals::is('flash', Modal::class))->toBeTrue()
        ->and(Globals::is('flash', Slide::class))->toBeTrue()
        ->and(Globals::is('flash', Card::class))->toBeFalse();
});

it('can exclude components from flash using the except list', function () {
    TallStackUi::customize()->globals()->flash(except: [Card::class]);

    expect(Globals::is('flash', Modal::class))->toBeTrue()
        ->and(Globals::is('flash', Slide::class))->toBeTrue()
        ->and(Globals::is('flash', Card::class))->toBeFalse();
});

it('throws exception when a component is in both flash only and except', function () {
    expect(fn () => TallStackUi::customize()->globals()->flash(
        only: [Modal::class],
        except: [Modal::class],
    ))->toThrow(InvalidArgumentException::class);
});

it('can reset globals', function () {
    TallStackUi::customize()->globals()->flash();

    expect(Globals::is('flash', Modal::class))->toBeTrue();

    Globals::reset();

    expect(Globals::is('flash', Modal::class))->toBeFalse();
});

it('can check unknown global without errors', function () {
    expect(Globals::is('unknown-global', Modal::class))->toBeFalse();
});

it('can remove transitions from modal when flash is global', function () {
    $component = <<<'HTML'
    <x-modal title="Foo">Bar</x-modal>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->globals()->flash();

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from modal when targeted', function () {
    $component = <<<'HTML'
    <x-modal title="Foo">Bar</x-modal>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->globals()->flash(only: [Modal::class]);

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from slide when flash is global', function () {
    $component = <<<'HTML'
    <x-slide title="Foo">Bar</x-slide>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->globals()->flash();

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from card when flash is global', function () {
    $component = <<<'HTML'
    <x-card header="Foo" minimize>Bar</x-card>
    HTML;

    expect($component)->render()->toContain('x-transition:enter');

    TallStackUi::customize()->globals()->flash();

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can remove transitions from carousel when flash is global', function () {
    $component = <<<'HTML'
    <x-carousel :images="[['image' => 'foo.jpg']]" />
    HTML;

    expect($component)->render()->toContain('x-transition.opacity');

    TallStackUi::customize()->globals()->flash();

    expect($component)->render()->not->toContain('x-transition.opacity');
});

it('does not affect untargeted components', function () {
    TallStackUi::customize()->globals()->flash(only: [Modal::class]);

    $card = <<<'HTML'
    <x-card header="Foo" minimize>Bar</x-card>
    HTML;

    expect($card)->render()->toContain('x-transition:enter');
});

it('can remove transitions from floating when flash is global', function () {
    TallStackUi::customize()->globals()->flash();

    $component = <<<'HTML'
    <x-floating>Content</x-floating>
    HTML;

    expect($component)->render()->not->toContain('x-transition:enter');
});

it('can use helper function with class string', function () {
    TallStackUi::customize()->globals()->flash(only: [Modal::class]);

    expect(__ts_global('flash', Modal::class))->toBeTrue()
        ->and(__ts_global('flash', Slide::class))->toBeFalse();
});

// Square global tests

it('can chain square global', function () {
    expect(TallStackUi::customize()->globals()->square())->toBeInstanceOf(Globals::class);
});

it('can remove rounded classes from card when square is global', function () {
    $component = '<x-card header="Foo">Bar</x-card>';

    expect($component)->render()->toContain('rounded-lg');

    TallStackUi::customize()->globals()->square();

    expect($component)->render()->not->toMatch('/\brounded-/');
});

it('can remove rounded classes from input when square is global', function () {
    $component = '<x-input label="Name" />';

    expect($component)->render()->toContain('rounded-md');

    TallStackUi::customize()->globals()->square();

    expect($component)->render()->not->toMatch('/\brounded-/');
});

it('can apply square only to components in the only list', function () {
    TallStackUi::customize()->globals()->square(only: [Card::class]);

    $card = '<x-card header="Foo">Bar</x-card>';
    $input = '<x-input label="Name" />';

    expect($card)->render()->not->toMatch('/\brounded-/')
        ->and($input)->render()->toContain('rounded-md');
});

it('can exclude components using the except list', function () {
    TallStackUi::customize()->globals()->square(except: [Card::class]);

    $card = '<x-card header="Foo">Bar</x-card>';
    $input = '<x-input label="Name" />';

    expect($card)->render()->toContain('rounded-lg')
        ->and($input)->render()->not->toMatch('/\brounded-/');
});

it('throws exception when a component is in both only and except', function () {
    expect(fn () => TallStackUi::customize()->globals()->square(
        only: [Card::class],
        except: [Card::class],
    ))->toThrow(InvalidArgumentException::class);
});
