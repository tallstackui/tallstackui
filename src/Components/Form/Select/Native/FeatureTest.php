<?php

use TallStackUi\Components\Form\Select\Native\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    $this->components = config('ts-ui.components');
});

afterEach(function () {
    config()->set('ts-ui.components', $this->components);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render')
    ->expect('<x-select.native />')
    ->render()
    ->toContain('<select');

it('can render with label')
    ->expect('<x-select.native label="Choose one" />')
    ->render()
    ->toContain('<select')
    ->toContain('Choose one');

it('can render with hint')
    ->expect('<x-select.native label="Choose one" hint="Pick your favorite" />')
    ->render()
    ->toContain('<select')
    ->toContain('Choose one')
    ->toContain('Pick your favorite');

it('can render with simple array options', function () {
    $component = <<<'HTML'
    <x-select.native :options="['Foo', 'Bar', 'Baz']" />
    HTML;

    expect($component)->render()
        ->toContain('<select')
        ->toContain('<option')
        ->toContain('Foo')
        ->toContain('Bar')
        ->toContain('Baz');
});

it('can render with selectable array options', function () {
    $component = <<<'HTML'
    <x-select.native :options="[['label' => 'Apple', 'value' => '1'], ['label' => 'Banana', 'value' => '2']]" select="label:label|value:value" />
    HTML;

    expect($component)->render()
        ->toContain('<select')
        ->toContain('<option')
        ->toContain('Apple')
        ->toContain('Banana');
});

it('can render with selectable keys from the global configuration', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'select.native' => [Component::class, ['select' => 'label:name|value:id']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    $component = <<<'HTML'
    <x-select.native :options="[['name' => 'Apple', 'id' => '1'], ['name' => 'Banana', 'id' => '2']]" />
    HTML;

    expect($component)->render()
        ->toContain('Apple')
        ->toContain('Banana')
        ->toContain('value="1"');
});

it('can override the global configuration keys inline', function () {
    config()->set('ts-ui.components', [
        ...config('ts-ui.components'),
        'select.native' => [Component::class, ['select' => 'label:name|value:id']],
    ]);

    __ts_get_component_configuration(Component::class, flush: true);

    $component = <<<'HTML'
    <x-select.native :options="[['title' => 'Apple', 'uuid' => '1']]" select="label:title|value:uuid" />
    HTML;

    expect($component)->render()
        ->toContain('Apple')
        ->toContain('value="1"');
});
