<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-kbd text="Ctrl" />')
    ->render()
    ->toContain('Ctrl')
    ->toContain('bg-gray-100');

it('can render slot')
    ->expect('<x-kbd>K</x-kbd>')
    ->render()
    ->toContain('K')
    ->toContain('bg-gray-100');

it('can render default size as sm')
    ->expect('<x-kbd>Ctrl</x-kbd>')
    ->render()
    ->toContain('text-sm')
    ->toContain('px-1.5');

it('can render size variations', function (array $size) {
    $key = array_key_first($size);
    $class = $size[$key];

    $component = <<<'HTML'
    <x-kbd {{ size }}>Ctrl</x-kbd>
    HTML;

    $component = str_replace('{{ size }}', $key, $component);

    expect($component)->render()
        ->toContain('Ctrl')
        ->toContain($class);
})->with([
    fn () => ['xs' => 'text-xs'],
    fn () => ['sm' => 'text-sm'],
    fn () => ['md' => 'text-md'],
    fn () => ['lg' => 'text-lg'],
]);

it('can render borderless')
    ->expect('<x-kbd borderless>Ctrl</x-kbd>')
    ->render()
    ->toContain('border-transparent')
    ->toContain('shadow-none');

it('can render with tooltip')
    ->expect('<x-kbd tooltip="Press this key">Ctrl</x-kbd>')
    ->render()
    ->toContain('x-tooltip="Press this key"')
    ->toContain('x-data');

it('can render as kbd tag')
    ->expect('<x-kbd>Ctrl</x-kbd>')
    ->render()
    ->toContain('<kbd')
    ->not->toContain('<a');

it('can render dark mode classes')
    ->expect('<x-kbd>Esc</x-kbd>')
    ->render()
    ->toContain('dark:bg-dark-600')
    ->toContain('dark:text-dark-300');

it('can render with monospace font')
    ->expect('<x-kbd>K</x-kbd>')
    ->render()
    ->toContain('font-mono')
    ->not->toContain('<code>');
