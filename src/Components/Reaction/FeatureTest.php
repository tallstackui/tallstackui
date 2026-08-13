<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Reaction\Component;
use TallStackUi\Customization\Globals;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    Globals::reset();

    __ts_get_component_configuration(Component::class, flush: true);

    livewireContext();

    // ReactionRuntime reads the Livewire component id, which the
    // shared instance only carries after being explicitly assigned.
    view()->shared('__livewire')->setId('reactions');
});

afterEach(function () {
    Globals::reset();
});

it('can render')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain('tallstackui_reaction');

it('cannot render a delay by default')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain("'auto', null, null, false)");

it('can render a named delay', function (string $delay) {
    expect(<<<HTML
    <x-reaction id="reactions" delay="$delay" />
    HTML)->render()->toContain("'auto', '$delay', null, false)");
})->with(['slow', 'fast', 'faster', 'flash']);

it('cannot use a bad delay', function () {
    $this->expectException(ViewException::class);

    expect('<x-reaction id="reactions" delay="foo" />')->render();
});

it('can render delay through the global configuration', function () {
    config()->set('ts-ui.components.reaction.1.delay', 'slow');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', 'slow', null, false)");

    config()->set('ts-ui.components.reaction.1.delay', null);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global delay through the inline prop', function () {
    config()->set('ts-ui.components.reaction.1.delay', 'slow');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" delay="faster" />')->render()->toContain("'auto', 'faster', null, false)");

    config()->set('ts-ui.components.reaction.1.delay', null);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render the flash delay when the global is enabled', function () {
    TallStackUi::customize()->globals()->flash();

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', 'flash', null, false)");
});

it('can render the flash delay when the global restricts to the component', function () {
    TallStackUi::customize()->globals()->flash(only: [Component::class]);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', 'flash', null, false)");
});

it('cannot render the flash delay when the global excludes the component', function () {
    TallStackUi::customize()->globals()->flash(except: [Component::class]);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', null, null, false)");
});

it('can suppress the flash global through the inline delay', function () {
    TallStackUi::customize()->globals()->flash();

    expect('<x-reaction id="reactions" delay="slow" />')->render()->toContain("'auto', 'slow', null, false)");
});

it('cannot render a balloon by default')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain("'auto', null, null, false)");

it('can render a balloon color')
    ->expect('<x-reaction id="reactions" balloon="red" />')
    ->render()
    ->toContain("'auto', null, 'red', false)");

it('cannot use a bad balloon', function () {
    $this->expectException(ViewException::class);

    expect('<x-reaction id="reactions" balloon="foo" />')->render();
});

it('can render balloon through the global configuration', function () {
    config()->set('ts-ui.components.reaction.1.balloon', 'emerald');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', null, 'emerald', false)");

    config()->set('ts-ui.components.reaction.1.balloon', null);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global balloon through the inline prop', function () {
    config()->set('ts-ui.components.reaction.1.balloon', 'emerald');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" balloon="red" />')->render()->toContain("'auto', null, 'red', false)");

    config()->set('ts-ui.components.reaction.1.balloon', null);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('cannot render hover by default')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain("'auto', null, null, false)")
    ->not->toContain('x-on:pointerenter');

it('can render hover')
    ->expect('<x-reaction id="reactions" hover />')
    ->render()
    ->toContain("'auto', null, null, true)")
    ->toContain('x-on:pointerenter');

it('can render hover through the global configuration', function () {
    config()->set('ts-ui.components.reaction.1.hover', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" />')->render()
        ->toContain("'auto', null, null, true)")
        ->toContain('x-on:pointerenter');

    config()->set('ts-ui.components.reaction.1.hover', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global hover through the inline prop', function () {
    config()->set('ts-ui.components.reaction.1.hover', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" :hover="false" />')->render()
        ->toContain("'auto', null, null, false)")
        ->not->toContain('x-on:pointerenter');

    config()->set('ts-ui.components.reaction.1.hover', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('prefers inline definitions over the configuration', function () {
    config()->set('ts-ui.components.reaction.1.delay', 'slow');
    config()->set('ts-ui.components.reaction.1.balloon', 'emerald');
    config()->set('ts-ui.components.reaction.1.hover', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" />')->render()
        ->toContain("'auto', 'slow', 'emerald', true)")
        ->toContain('x-on:pointerenter');

    expect('<x-reaction id="reactions" delay="faster" balloon="red" :hover="false" />')->render()
        ->toContain("'auto', 'faster', 'red', false)")
        ->not->toContain('x-on:pointerenter');

    config()->set('ts-ui.components.reaction.1.delay', null);
    config()->set('ts-ui.components.reaction.1.balloon', null);
    config()->set('ts-ui.components.reaction.1.hover', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('prefers the configured delay over the flash global', function () {
    config()->set('ts-ui.components.reaction.1.delay', 'slow');

    __ts_get_component_configuration(Component::class, flush: true);

    TallStackUi::customize()->globals()->flash();

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', 'slow', null, false)");

    config()->set('ts-ui.components.reaction.1.delay', null);

    __ts_get_component_configuration(Component::class, flush: true);
});
