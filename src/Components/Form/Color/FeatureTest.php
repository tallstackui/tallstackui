<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Color\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-color />')
    ->render()
    ->toContain('<input');

it('can render in picker mode')
    ->expect('<x-color picker />')
    ->render()
    ->toContain('<input');

it('can render with custom colors', function () {
    $component = <<<'HTML'
    <x-color :colors="['#fff', '#000']" />
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('#fff')
        ->toContain('#000');
});

it('can render with label')
    ->expect('<x-color label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-color label="Foo bar" hint="Bar baz" />')->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');

it('cannot render with excluded step without picker', function () {
    $this->expectException(ViewException::class);

    expect('<x-color excluded-step="500" />')->render();
});

it('cannot render with invalid excluded color', function () {
    $this->expectException(ViewException::class);

    expect('<x-color excluded-color="invalid" />')->render();
});

it('cannot render with invalid excluded step', function () {
    $this->expectException(ViewException::class);

    expect('<x-color excluded-step="999" />')->render();
});

it('can render picker mode through the global configuration', function () {
    config()->set('ts-ui.components.color.1.picker', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-color />')->render()->toContain("'picker'");

    config()->set('ts-ui.components.color.1.picker', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global picker through the inline prop', function () {
    config()->set('ts-ui.components.color.1.picker', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-color :picker="false" />')->render()->toContain("'range'");

    config()->set('ts-ui.components.color.1.picker', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render excluded step when the picker comes from the global configuration', function () {
    config()->set('ts-ui.components.color.1.picker', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-color excluded-step="50" />')->render()->toContain('<input');

    config()->set('ts-ui.components.color.1.picker', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render selectable through the global configuration', function () {
    config()->set('ts-ui.components.color.1.selectable', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-color />')->render()->toContain('caret-transparent');

    config()->set('ts-ui.components.color.1.selectable', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render clearable through the global configuration', function () {
    config()->set('ts-ui.components.color.1.clearable', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-color />')->render()->toContain('tallstackui_form_color_clearable');

    config()->set('ts-ui.components.color.1.clearable', false);

    __ts_get_component_configuration(Component::class, flush: true);
});
