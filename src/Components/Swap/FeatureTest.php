<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Swap\Component;
use TallStackUi\Customization\Globals;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    __ts_get_component_configuration(Component::class, flush: true);

    Globals::reset();
});

it('can render')
    ->expect('<x-swap :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('tallstackui_swap');

it('can render label and hint')
    ->expect('<x-swap label="Fruits" hint="Pick one" :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('Fruits')
    ->toContain('Pick one');

it('can render as block')
    ->expect('<x-swap block :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('flex-1');

it('can render without preview by default')
    ->expect('<x-swap :options="[\'foo\', \'bar\']" />')
    ->render()
    ->not->toContain('mask-image');

it('can render preview through attribute')
    ->expect('<x-swap preview :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('mask-image');

it('can render preview slots as thirds')
    ->expect('<x-swap preview :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('w-1/3');

it('can enable preview through global configuration', function () {
    config()->set('ts-ui.components.swap.1.preview', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-swap :options="[\'foo\', \'bar\']" />')->render()
        ->toContain('mask-image');
});

it('can render vertical')
    ->expect('<x-swap vertical :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('flex-col');

it('can enable vertical through global configuration', function () {
    config()->set('ts-ui.components.swap.1.vertical', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-swap :options="[\'foo\', \'bar\']" />')->render()
        ->toContain('flex-col');
});

it('can render keyboard navigation on the buttons')
    ->expect('<x-swap :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('x-on:keydown.left.prevent')
    ->toContain('x-on:keydown.right.prevent');

it('can render vertical keyboard navigation on the buttons')
    ->expect('<x-swap vertical :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('x-on:keydown.up.prevent')
    ->toContain('x-on:keydown.down.prevent');

it('can render without loop')
    ->expect('<x-swap :loop="false" :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('tallstackui_swap');

it('cannot make the viewport focusable')
    ->expect('<x-swap :options="[\'foo\', \'bar\']" />')
    ->render()
    ->not->toContain('tabindex');

it('cannot use preview with vertical', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [preview] and [vertical] cannot be used together.');

    expect('<x-swap preview vertical :options="[\'foo\', \'bar\']" />')->render();
});

it('can use options as collection')
    ->expect('<x-swap :options="collect([\'foo\', \'bar\'])" />')
    ->render()
    ->toContain('tallstackui_swap');

it('can use dimensional options with custom keys')
    ->expect('<x-swap :options="[[\'name\' => \'Foo\', \'id\' => 1], [\'name\' => \'Bar\', \'id\' => 2]]" select="label:name|value:id" />')
    ->render()
    ->toContain('tallstackui_swap');

it('can use dimensional options with keys from the global configuration', function () {
    config()->set('ts-ui.components.swap.1.select', 'label:name|value:id');

    __ts_get_component_configuration(Component::class, flush: true);

    $expected = base64_encode(json_encode([['label' => 'Foo', 'value' => 1], ['label' => 'Bar', 'value' => 2]]));

    expect('<x-swap :options="[[\'name\' => \'Foo\', \'id\' => 1], [\'name\' => \'Bar\', \'id\' => 2]]" />')->render()
        ->toContain($expected);
});

it('can override the global configuration keys inline', function () {
    config()->set('ts-ui.components.swap.1.select', 'label:name|value:id');

    __ts_get_component_configuration(Component::class, flush: true);

    $expected = base64_encode(json_encode([['label' => 'Foo', 'value' => 1]]));

    expect('<x-swap :options="[[\'title\' => \'Foo\', \'uuid\' => 1]]" select="label:title|value:uuid" />')->render()
        ->toContain($expected);
});

it('cannot use dimensional options missing keys', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The key [value] is missing in the options array.');

    expect('<x-swap :options="[[\'label\' => \'Foo\']]" />')->render();
});

it('can remove transitions when flash is global', function () {
    $component = <<<'HTML'
    <x-swap :options="['foo', 'bar']" />
    HTML;

    expect($component)->render()->toContain('transition-transform');

    TallStackUi::customize()->globals()->flash();

    expect($component)->render()->not->toContain('transition-transform');
});

it('can remove transitions when flash targets the component', function () {
    TallStackUi::customize()->globals()->flash(only: [Component::class]);

    expect('<x-swap preview :options="[\'foo\', \'bar\']" />')->render()
        ->not->toContain('transition-transform')
        ->not->toContain('transition-[opacity,transform]');
});

it('can render with tooltip')
    ->expect('<x-swap tooltip="Swap me" :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('x-tooltip');

it('can render disabled')
    ->expect('<x-swap disabled :options="[\'foo\', \'bar\']" />')
    ->render()
    ->toContain('cursor-not-allowed');
