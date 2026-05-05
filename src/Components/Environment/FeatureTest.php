<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-environment />')
    ->render()
    ->toContain('Environment:');

it('can render square')
    ->expect('<x-environment square />')
    ->render()
    ->not->toContain('rounded-md')
    ->not->toContain('rounded-full');

it('can render round')
    ->expect('<x-environment round />')
    ->render()
    ->toContain('rounded-full')
    ->not->toContain('rounded-md');

it('can render round with named size', function (string $size, string $class) {
    $component = "<x-environment round=\"$size\" />";

    expect($component)->render()->toContain($class);
})->with([
    ['xs', 'rounded-xs'],
    ['sm', 'rounded-sm'],
    ['md', 'rounded-md'],
    ['lg', 'rounded-lg'],
    ['xl', 'rounded-xl'],
]);

it('cannot accept invalid round value', function () {
    $this->expectException(ViewException::class);

    expect('<x-environment round="huge" />')->render();
});

it('can render left slot', function () {
    $component = <<<'HTML'
    <x-environment>
        <x-slot:left>Left slot</x-slot:left>
    </x-environment>
    HTML;

    expect($component)->render()->toContain('Left slot');
});

it('can render right slot', function () {
    $component = <<<'HTML'
    <x-environment>
        <x-slot:right>Right slot</x-slot:right>
    </x-environment>
    HTML;

    expect($component)->render()->toContain('Right slot');
});

it('can render size variations', function (array $size) {
    $key = array_key_first($size);
    $class = $size[$key];

    $component = <<<'HTML'
    <x-environment {{ size }} />
    HTML;

    $component = str_replace('{{ size }}', $key, $component);

    expect($component)->render()
        ->toContain('Environment')
        ->toContain($class);
})->with([
    fn () => ['xs' => 'text-xs'],
    fn () => ['sm' => 'text-sm'],
    fn () => ['md' => 'text-md'],
    fn () => ['lg' => 'text-lg'],
]);
