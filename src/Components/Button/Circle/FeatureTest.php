<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Button\Normal\Component as NormalComponent;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

afterEach(function () {
    config()->set('ts-ui.components.button.1.spinner', null);

    __ts_get_component_configuration(NormalComponent::class, flush: true);
});

it('can render with slot')
    ->expect('<x-button.circle>Foo bar</x-button.circle>')
    ->render()
    ->toContain('Foo bar');

it('can render with text')
    ->expect('<x-button.circle text="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render round')
    ->expect('<x-button.circle text="Foo bar" round />')
    ->render()
    ->toContain('rounded-full');

it('can render with icon')
    ->expect('<x-button.circle text="Foo bar" icon="users" />')
    ->render()
    ->toContain('<svg');

it('can render as tag a')
    ->expect('<x-button.circle href="https://google.com.br" target="_blank">Foo bar</x-button.circle>')
    ->render()
    ->toContain('<a  href="https://google.com.br"')
    ->toContain('_blank');

it('can render as tag a using raw html')
    ->expect('<x-button.circle href="https://google.com.br/?foo=bar&baz=bah" target="_blank">Foo bar</x-button.circle>')
    ->render()
    ->toContain('<a  href="https://google.com.br/?foo=bar&baz=bah')
    ->toContain('_blank');

it('can render colored', function (string $colors) {
    $component = <<<HTML
    <x-button.circle text="Foo bar" color="$colors" />
    HTML;

    $color = match ($colors) {
        'white' => 'bg-white',
        'black' => 'bg-black',
        default => "bg-$colors-500",
    };

    expect($component)->render()
        ->toContain($color);
})->with(colorsDataset());

it('can render lg', function () {
    expect('<x-button.circle text="LG" color="primary" lg />')->render()
        ->toContain('w-12 h-12')
        ->toContain('text-lg')
        ->and('<x-button.circle icon="users" color="primary" lg />')->render()
        ->toContain('w-12 h-12')
        ->toContain('w-6 h-6');

});

it('can render md', function () {
    expect('<x-button.circle text="MD" color="primary" />')->render()
        ->toContain('w-9 h-9')
        ->toContain('text-md')
        ->and('<x-button.circle icon="users" color="primary" />')->render()
        ->toContain('w-9 h-9')
        ->toContain('w-4 h-4');

});

it('can render sm', function () {
    expect('<x-button.circle sm text="MD" color="primary" />')->render()
        ->toContain('w-6 h-6')
        ->toContain('text-sm')
        ->and('<x-button.circle sm icon="users" color="primary" />')->render()
        ->toContain('w-6 h-6')
        ->toContain('w-3 h-3');

});

it('can render xs', function () {
    expect('<x-button.circle xs text="MD" color="primary" />')->render()
        ->toContain('w-4 h-4')
        ->toContain('text-xs')
        ->and('<x-button.circle xs icon="users" color="primary" />')->render()
        ->toContain('w-4 h-4')
        ->toContain('w-2 h-2');

});

it('can render with type submit')
    ->expect('<x-button.circle icon="pencil" submit />')
    ->render()
    ->toContain('type="submit"', false);

it('emits data-tsui-unfocus when unfocus is on')
    ->expect('<x-button.circle icon="pencil" color="primary" unfocus />')
    ->render()
    ->toContain('data-tsui-unfocus');

it('does not emit data-tsui-unfocus by default')
    ->expect('<x-button.circle icon="pencil" color="primary" />')
    ->render()
    ->not->toContain('data-tsui-unfocus');

it('can render the default gradient loading spinner', function () {
    livewireContext();

    expect('<x-button.circle icon="trash" loading="delete" />')->render()
        ->toContain('dusk="button-loading-spinner"')
        ->toContain('dusk="spinner-gradient"')
        ->toContain('wire:target="delete"');
});

it('can render loading spinner variants', function (string $spinner) {
    livewireContext();

    $component = <<<HTML
    <x-button.circle icon="trash" loading="delete" spinner="$spinner" />
    HTML;

    expect($component)->render()
        ->toContain('dusk="spinner-'.$spinner.'"');
})->with([
    'ring',
    'throbber',
    'gradient',
    'ping',
    'dots',
    'pulse',
    'typing',
    'bars',
    'wave',
]);

it('cannot render the loading spinner outside the livewire context')
    ->expect('<x-button.circle icon="trash" loading="delete" spinner="dots" />')
    ->render()
    ->not->toContain('dusk="button-loading-spinner"');

it('can use the global spinner configuration', function () {
    config()->set('ts-ui.components.button.1.spinner', 'bars');

    __ts_get_component_configuration(NormalComponent::class, flush: true);

    livewireContext();

    expect('<x-button.circle icon="trash" loading="delete" />')->render()
        ->toContain('dusk="spinner-bars"');
});

it('can thrown exception when spinner is unnaceptable', function (string $spinner) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Button\Circle: The [spinner] must be one of: [ring, throbber, gradient, ping, dots, pulse, typing, bars, wave].');

    $component = <<<HTML
    <x-button.circle icon="trash" spinner="$spinner" />
    HTML;

    expect($component)->render();
})->with([
    'shimmer',
    'caret',
    'terminal',
    'thinking',
    'foo',
]);
