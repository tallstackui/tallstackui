<?php

it('can render')
    ->expect('<x-progress percent="35" />')
    ->render()
    ->toContain('35%')
    ->toContain('bg-primary-600');

it('can render size variations', function (array $size) {
    $key = array_key_first($size);
    $class = $size[$key];

    $component = <<<'HTML'
    <x-progress percent="35" {{ size }} />
    HTML;

    $component = str_replace('{{ size }}', $key, $component);

    expect($component)->render()
        ->toContain('35%')
        ->toContain($class);
})->with([
    fn () => ['xs' => 'h-2.5'],
    fn () => ['sm' => 'h-3'],
    fn () => ['md' => 'h-4'],
    fn () => ['lg' => 'h-5'],
]);

it('can render red color')
    ->expect('<x-progress percent="35" color="red" />')
    ->render()
    ->toContain('35%')
    ->toContain('bg-red-600');

it('can render simple variation')
    ->expect('<x-progress percent="35" simple />')
    ->render()
    ->toContain('35%');

it('can render title variation')
    ->expect('<x-progress percent="35" title="Foo Bar" />')
    ->render()
    ->toContain('35%')
    ->toContain('Foo Bar');

it('can render floating variation')
    ->expect('<x-progress percent="35" floating />')
    ->render()
    ->toContain('35%')
    ->toContain('margin-inline-start');

it('can render progress circle')
    ->expect('<x-progress.circle percent="35" />')
    ->render()
    ->toContain('<svg')
    ->toContain('35%');