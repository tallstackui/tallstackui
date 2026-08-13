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

it('can render')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain('tallstackui_reaction');

it('cannot render a delay by default')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain("'auto', null)");

it('can render a named delay', function (string $delay) {
    expect(<<<HTML
    <x-reaction id="reactions" delay="$delay" />
    HTML)->render()->toContain("'auto', '$delay')");
})->with(['slow', 'fast', 'faster', 'flash']);

it('cannot use a bad delay', function () {
    $this->expectException(ViewException::class);

    expect('<x-reaction id="reactions" delay="foo" />')->render();
});

it('can render delay through the global configuration', function () {
    config()->set('ts-ui.components.reaction.1.delay', 'slow');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', 'slow')");

    config()->set('ts-ui.components.reaction.1.delay', null);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global delay through the inline prop', function () {
    config()->set('ts-ui.components.reaction.1.delay', 'slow');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-reaction id="reactions" delay="faster" />')->render()->toContain("'auto', 'faster')");

    config()->set('ts-ui.components.reaction.1.delay', null);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render the flash delay when the global is enabled', function () {
    TallStackUi::customize()->globals()->flash();

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', 'flash')");
});

it('can render the flash delay when the global restricts to the component', function () {
    TallStackUi::customize()->globals()->flash(only: [Component::class]);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', 'flash')");
});

it('cannot render the flash delay when the global excludes the component', function () {
    TallStackUi::customize()->globals()->flash(except: [Component::class]);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', null)");
});

it('can suppress the flash global through the inline delay', function () {
    TallStackUi::customize()->globals()->flash();

    expect('<x-reaction id="reactions" delay="slow" />')->render()->toContain("'auto', 'slow')");
});
