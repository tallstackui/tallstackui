<?php

it('can render with slot', function () {
    $this->blade('<x-button.circle>Foo bar</x-button.circle>')
        ->assertSee('Foo bar');
});

it('can render with text', function () {
    $this->blade('<x-button.circle text="Foo bar" />')
        ->assertSee('Foo bar');
});

it('can render round', function () {
    $this->blade('<x-button.circle text="Foo bar" round />')
        ->assertSee('rounded-full');
});

it('can render with icon', function () {
    $this->blade('<x-button.circle text="Foo bar" icon="users" />')
        ->assertSee('<svg', false);
});

it('can render as tag a', function () {
    $this->blade('<x-button.circle href="https://google.com.br" target="_blank">Foo bar</x-button.circle>')
        ->assertSee('https://google.com.br')
        ->assertSee('<a', false)
        ->assertSee('_blank');
});

it('can render colored', function (string $colors) {
    $component = <<<HTML
    <x-button.circle text="Foo bar" color="$colors" />
    HTML;

    $color = match ($colors) {
        'white', 'black' => 'bg-neutral',
        default => "bg-$colors-500",
    };

    $this->blade($component)->assertSee($color);
})->with('colors')->skip();

it('can render lg', function () {
    $this->blade('<x-button.circle text="LG" color="primary" lg />')
        ->assertSee('w-12 h-12')
        ->assertSee('text-xl');

    $this->blade('<x-button.circle icon="users" color="primary" lg />')
        ->assertSee('w-12 h-12')
        ->assertSee('w-6 h-6');
});

it('can render md', function () {
    $this->blade('<x-button.circle text="MD" color="primary" />')
        ->assertSee('w-9 h-9')
        ->assertSee('text-md');

    $this->blade('<x-button.circle icon="users" color="primary" />')
        ->assertSee('w-9 h-9')
        ->assertSee('w-4 h-4');
});

it('can render sm', function () {
    $this->blade('<x-button.circle sm text="MD" color="primary" />')
        ->assertSee('w-6 h-6')
        ->assertSee('text-xs');

    $this->blade('<x-button.circle sm icon="users" color="primary" />')
        ->assertSee('w-6 h-6')
        ->assertSee('w-3 h-3');
});
