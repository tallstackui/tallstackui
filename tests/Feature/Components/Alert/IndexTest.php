<?php

it('can render title')
    ->expect('<x-alert title="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render text')
    ->expect('<x-alert text="Bar foo" />')
    ->render()
    ->toContain('Bar foo')
    ->toContain('bg-primary-600');

it('can render slot')
    ->expect('<x-alert>Foo bar</x-alert>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-600');

it('can render with footer slot', function () {
    $component = <<<'HTML'
        <x-alert>
            Foo bar
            <x-slot:footer>
                <button>Button</button>
            </x-slot:footer>
        </x-alert>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Button');
});

it('can render close alert')
    ->expect('<x-alert text="Foo bar" close />')
    ->render()
    ->toContain('<svg class="w-5 h-5 text-primary-50"');

it('can render light')
    ->expect('<x-alert text="Foo bar" light />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-primary-50')
    ->not->toContain('bg-primary-600');

it('can render black background with white text')
    ->expect('<x-alert text="Foo bar" color="black" />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-black')
    ->toContain('text-white')
    ->not->toContain('text-black');

it('can render white background with black text')
    ->expect('<x-alert text="Foo bar" color="white" />')
    ->render()
    ->toContain('Foo bar')
    ->toContain('bg-white')
    ->toContain('text-black')
    ->not->toContain('text-white');
