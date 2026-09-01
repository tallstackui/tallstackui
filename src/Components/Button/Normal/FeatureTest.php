<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Button\Normal\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

afterEach(function () {
    config()->set('ts-ui.components.button.1.spinner', null);
    config()->set('ts-ui.components.button.1.round', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render with slot')
    ->expect('<x-button>Foo bar</x-button>')
    ->render()
    ->toContain('Foo bar');

it('can render with text')
    ->expect('<x-button text="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render xs')
    ->expect('<x-button text="Foo bar" xs />')
    ->render()
    ->toContain('px-1 py-0.5');

it('can render sm')
    ->expect('<x-button text="Foo bar" sm />')
    ->render()
    ->toContain('px-2 py-1');

it('can render md')
    ->expect('<x-button text="Foo bar" md />')
    ->render()
    ->toContain('px-4 py-2');

it('can render lg')
    ->expect('<x-button text="Foo bar" lg />')
    ->render()
    ->toContain('px-6 py-3');

it('can render square')
    ->expect('<x-button text="Foo bar" square />')
    ->render()
    ->not->toContain('rounded');

it('can render round')
    ->expect('<x-button text="Foo bar" round />')
    ->render()
    ->toContain('rounded-full');

it('can render rounded-md by default')
    ->expect('<x-button text="Foo bar" />')
    ->render()
    ->toContain('rounded-md');

it('can render round with a size', function (string $round, string $class) {
    $component = <<<HTML
    <x-button text="Foo bar" round="$round" />
    HTML;

    expect($component)->render()
        ->toContain($class);
})->with([
    ['xs', 'rounded-xs'],
    ['sm', 'rounded-sm'],
    ['md', 'rounded-md'],
    ['lg', 'rounded-lg'],
    ['xl', 'rounded-xl'],
    ['full', 'rounded-full'],
]);

it('cannot render round when square is on')
    ->expect('<x-button text="Foo bar" square round="lg" />')
    ->render()
    ->not->toContain('rounded');

it('can render round through the global configuration', function () {
    config()->set('ts-ui.components.button.1.round', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-button text="Foo bar" />')->render()
        ->toContain('rounded-full')
        ->not->toContain('rounded-md');
});

it('can render a named round through the global configuration', function () {
    config()->set('ts-ui.components.button.1.round', 'lg');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-button text="Foo bar" />')->render()->toContain('rounded-lg');
});

it('can suppress the global round through the inline prop', function () {
    config()->set('ts-ui.components.button.1.round', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-button text="Foo bar" :round="false" />')->render()
        ->not->toContain('rounded-full')
        ->toContain('rounded-md');
});

it('can thrown exception when the global round is unnaceptable', function () {
    config()->set('ts-ui.components.button.1.round', '2xl');

    __ts_get_component_configuration(Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Button\Normal: The [round] must be true or one of: [xs, sm, md, lg, xl, full].');

    expect('<x-button text="Foo bar" />')->render();
});

it('can thrown exception when round is unnaceptable', function (string $round) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Button\Normal: The [round] must be true or one of: [xs, sm, md, lg, xl, full].');

    $component = <<<HTML
    <x-button text="Foo bar" round="$round" />
    HTML;

    expect($component)->render();
})->with([
    'foo',
    'true',
    '2xl',
    'circle',
]);

it('can render as tag a')
    ->expect('<x-button href="https://google.com.br" text="Foo bar" round />')->render()
    ->toContain('<a  href="https://google.com.br"')
    ->not->toContain('<button');

it('can render as tag a using raw html')
    ->expect('<x-button href="https://google.com.br/?foo=bar&bar=baz" text="Foo bar" round />')->render()
    ->toContain('<a  href="https://google.com.br/?foo=bar&bar=baz"')
    ->not->toContain('<button');

it('can render with icon')
    ->expect('<x-button text="Foo bar" icon="users" />')
    ->render()
    ->toContain('<svg');

it('can render with type submit')
    ->expect('<x-button text="Foo bar" submit />')
    ->render()
    ->toContain('type="submit"', false);

it('can render block')
    ->expect('<x-button text="Foo bar" block />')
    ->render()
    ->toContain('w-full');

it('cannot render block class without block property')
    ->expect('<x-button text="Foo bar" />')
    ->render()
    ->not->toContain('w-full');

it('can render colored', function (string $colors) {
    $component = <<<HTML
    <x-button text="Foo bar" color="$colors" />
    HTML;

    $color = match ($colors) {
        'white' => 'bg-white',
        'black' => 'bg-black',
        default => "bg-$colors-500",
    };

    expect($component)->render()
        ->toContain($color);
})->with(colorsDataset());

it('can render the default gradient loading spinner', function () {
    livewireContext();

    expect('<x-button text="Foo bar" loading="save" />')->render()
        ->toContain('dusk="button-loading-spinner"')
        ->toContain('dusk="spinner-gradient"')
        ->toContain('wire:target="save"');
});

it('can render loading spinner variants', function (string $spinner) {
    livewireContext();

    $component = <<<HTML
    <x-button text="Foo bar" loading="save" spinner="$spinner" />
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
    ->expect('<x-button text="Foo bar" loading="save" spinner="dots" />')
    ->render()
    ->not->toContain('dusk="button-loading-spinner"');

it('can use the global spinner configuration', function () {
    config()->set('ts-ui.components.button.1.spinner', 'dots');

    __ts_get_component_configuration(Component::class, flush: true);

    livewireContext();

    expect('<x-button text="Foo bar" loading="save" />')->render()
        ->toContain('dusk="spinner-dots"');
});

it('cannot use an invalid global spinner configuration', function () {
    config()->set('ts-ui.components.button.1.spinner', 'shimmer');

    __ts_get_component_configuration(Component::class, flush: true);

    $this->expectException(ViewException::class);

    expect('<x-button text="Foo bar" />')->render();
});

it('can thrown exception when spinner is unnaceptable', function (string $spinner) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Button\Normal: The [spinner] must be one of: [ring, throbber, gradient, ping, dots, pulse, typing, bars, wave].');

    $component = <<<HTML
    <x-button text="Foo bar" spinner="$spinner" />
    HTML;

    expect($component)->render();
})->with([
    'shimmer',
    'caret',
    'terminal',
    'thinking',
    'foo',
]);

it('emits data-tsui-unfocus when unfocus is on')
    ->expect('<x-button text="Foo bar" color="primary" unfocus />')
    ->render()
    ->toContain('data-tsui-unfocus');

it('does not emit data-tsui-unfocus by default')
    ->expect('<x-button text="Foo bar" color="primary" />')
    ->render()
    ->not->toContain('data-tsui-unfocus');

it('can render subtle')
    ->expect('<x-button text="Foo bar" color="red" subtle />')
    ->render()
    ->toContain('border-gray-200', 'bg-white', 'hover:bg-gray-50', 'text-red-600', 'focus:ring-red-600')
    ->not->toContain('bg-red-500', 'border-red-600');

it('can render subtle tinted')
    ->expect('<x-button text="Foo bar" color="red" subtle tinted />')
    ->render()
    ->toContain('border-gray-200', 'bg-white', 'hover:bg-red-50', 'hover:border-red-300', 'text-red-600')
    ->not->toContain('hover:bg-gray-50');

it('can render subtle with icon')
    ->expect('<x-button text="Foo bar" color="red" subtle icon="check" />')
    ->render()
    ->toContain('text-red-600 dark:text-red-400');

it('cannot use tinted without subtle', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [tinted] can only be used with [subtle].');

    expect('<x-button text="Foo bar" tinted />')->render();
});
