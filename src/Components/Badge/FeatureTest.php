<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Badge\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-badge text="Foo bar" />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-500');

it('can render slot')
    ->expect('<x-badge>Foo bar</x-badge>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-500');

it('can render square')
    ->expect('<x-badge square>Foo bar</x-badge>')
    ->render()
    ->toContain('Foo bar')
    ->not->toContain('rounded-md');

it('can render round')
    ->expect('<x-badge round>Foo bar</x-badge>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('rounded-full')
    ->not->toContain('rounded-md');

it('can render round with named size', function (string $size, string $class) {
    $component = "<x-badge round=\"$size\">Foo bar</x-badge>";

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain($class);
})->with([
    ['xs', 'rounded-xs'],
    ['sm', 'rounded-sm'],
    ['md', 'rounded-md'],
    ['lg', 'rounded-lg'],
    ['xl', 'rounded-xl'],
]);

it('cannot accept invalid round value', function () {
    $this->expectException(ViewException::class);

    expect('<x-badge round="huge">Foo bar</x-badge>')->render();
});

it('can render round through the global configuration', function () {
    config()->set('ts-ui.components.badge.1.round', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-badge>Foo bar</x-badge>')->render()
        ->toContain('rounded-full')
        ->not->toContain('rounded-md');

    config()->set('ts-ui.components.badge.1.round', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render a named round through the global configuration', function () {
    config()->set('ts-ui.components.badge.1.round', 'lg');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-badge>Foo bar</x-badge>')->render()->toContain('rounded-lg');

    config()->set('ts-ui.components.badge.1.round', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can suppress the global round through the inline prop', function () {
    config()->set('ts-ui.components.badge.1.round', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-badge :round="false">Foo bar</x-badge>')->render()
        ->not->toContain('rounded-full')
        ->toContain('rounded-md');

    config()->set('ts-ui.components.badge.1.round', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render size variations', function (array $size) {
    $key = array_key_first($size);
    $class = $size[$key];

    $component = <<<'HTML'
    <x-badge {{ size }}>Foo bar</x-badge>
    HTML;

    $component = str_replace('{{ size }}', $key, $component);

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain($class);
})->with([
    fn () => ['xs' => 'text-xs'],
    fn () => ['sm' => 'text-sm'],
    fn () => ['md' => 'text-md'],
    fn () => ['lg' => 'text-lg'],
]);

it('can render outline')
    ->expect('<x-badge outline>Foo bar</x-badge>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('border-primary-600')
    ->toContain('text-primary-600');

it('can render icon on left')
    ->expect('<x-badge icon="users" position="left" text="Foo bar" />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-500')
    ->toContain('mr-1');

it('can render icon on right')
    ->expect('<x-badge icon="users" position="right" text="Foo bar" />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-500')
    ->toContain('ml-1');

it('can render left slot', function () {
    $component = <<<'HTML'
    <x-badge position="left" text="Foo bar">
        <x-slot:left>
            Left
        </x-slot:left>
    </x-badge>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Left')
        ->not->toContain('<svg');
});

it('can render right slot', function () {
    $component = <<<'HTML'
    <x-badge position="left" text="Foo bar">
        <x-slot:right>
            Right
        </x-slot:right>
    </x-badge>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Right')
        ->not->toContain('<svg');
});

it('can render the size through the global configuration', function () {
    config()->set('ts-ui.components.badge.1.size', 'lg');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-badge>Foo bar</x-badge>')->render()
        ->toContain('text-lg')
        ->not->toContain('text-xs');

    config()->set('ts-ui.components.badge.1.size', 'xs');

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can override the global size through the inline prop', function () {
    config()->set('ts-ui.components.badge.1.size', 'lg');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-badge sm>Foo bar</x-badge>')->render()
        ->toContain('text-sm')
        ->not->toContain('text-lg');

    config()->set('ts-ui.components.badge.1.size', 'xs');

    __ts_get_component_configuration(Component::class, flush: true);
});

it('cannot accept an invalid global size', function () {
    config()->set('ts-ui.components.badge.1.size', 'huge');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('The [size] configuration must be one of: [xs, sm, md, lg].');

        expect('<x-badge>Foo bar</x-badge>')->render();
    } finally {
        config()->set('ts-ui.components.badge.1.size', 'xs');

        __ts_get_component_configuration(Component::class, flush: true);
    }
});
