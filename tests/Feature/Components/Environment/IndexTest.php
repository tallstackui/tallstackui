<?php

it('can render')
    ->expect('<x-environment />')
    ->render()
    ->toContain('Environment:');

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
