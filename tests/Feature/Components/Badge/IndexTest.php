<?php

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
