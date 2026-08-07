<?php

use TallStackUi\Components\Form\Number\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-number />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-number label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-number label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');

it('uses numeric inputmode for positive integers')
    ->expect('<x-number min="0" step="1" />')
    ->render()
    ->toContain('inputmode="numeric"')
    ->toContain('pattern="[0-9]*"');

it('uses decimal inputmode for positive decimals')
    ->expect('<x-number min="0" step="0.01" />')
    ->render()
    ->toContain('inputmode="decimal"')
    ->toContain('pattern="[0-9]*[.,]?[0-9]*"');

it('uses text inputmode when min is negative')
    ->expect('<x-number min="-100" step="1" />')
    ->render()
    ->toContain('inputmode="text"')
    ->toContain('pattern="-?[0-9]*[.,]?[0-9]*"');

it('uses text inputmode when min is not set')
    ->expect('<x-number step="1" />')
    ->render()
    ->toContain('inputmode="text"')
    ->toContain('pattern="-?[0-9]*[.,]?[0-9]*"');

it('uses decimal inputmode for decimals with positive min')
    ->expect('<x-number min="10" step="0.5" />')
    ->render()
    ->toContain('inputmode="decimal"')
    ->toContain('pattern="[0-9]*[.,]?[0-9]*"');

it('can render plus and minus icons by default')
    ->expect('<x-number />')
    ->render()
    ->toContain('M12 3.75a');

it('can render chevron through the global configuration', function () {
    config()->set('ts-ui.components.number.1.chevron', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-number />')->render()->toContain('M11.47 7.72a');

    config()->set('ts-ui.components.number.1.chevron', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render centralized through the global configuration', function () {
    config()->set('ts-ui.components.number.1.centralized', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-number />')->render()->toContain('text-center');

    config()->set('ts-ui.components.number.1.centralized', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global centralized through the inline prop', function () {
    config()->set('ts-ui.components.number.1.centralized', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-number :centralized="false" />')->render()->not->toContain('text-center');

    config()->set('ts-ui.components.number.1.centralized', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render selectable through the global configuration', function () {
    config()->set('ts-ui.components.number.1.selectable', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-number />')->render()->toContain('caret-transparent');

    config()->set('ts-ui.components.number.1.selectable', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render the delay through the global configuration', function () {
    config()->set('ts-ui.components.number.1.delay', 7);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-number />')->render()->toContain('null, null, 7');

    config()->set('ts-ui.components.number.1.delay', 2);

    __ts_get_component_configuration(Component::class, flush: true);
});
