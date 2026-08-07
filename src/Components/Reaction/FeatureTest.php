<?php

use TallStackUi\Components\Reaction\Component;
use TallStackUi\Customization\Globals;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    Globals::reset();

    livewireContext();

    // ReactionRuntime reads the Livewire component id, which the
    // shared instance only carries after being explicitly assigned.
    view()->shared('__livewire')->setId('reactions');
});

it('can render')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain('tallstackui_reaction');

it('cannot render the flash flag by default')
    ->expect('<x-reaction id="reactions" />')
    ->render()
    ->toContain("'auto', false)");

it('can render the flash flag when the global is enabled', function () {
    TallStackUi::customize()->globals()->flash();

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', true)");
});

it('can render the flash flag when the global restricts to the component', function () {
    TallStackUi::customize()->globals()->flash(only: [Component::class]);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', true)");
});

it('cannot render the flash flag when the global excludes the component', function () {
    TallStackUi::customize()->globals()->flash(except: [Component::class]);

    expect('<x-reaction id="reactions" />')->render()->toContain("'auto', false)");
});
