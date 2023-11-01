<?php

it('can render', function () {
    $this->blade('<x-badge text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500');
});

it('can render slot', function () {
    $this->blade('<x-badge>Foo bar</x-badge>')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500');
});

it('can render square', function () {
    $this->blade('<x-badge square>Foo bar</x-badge>')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md');
});

it('can render round', function () {
    $this->blade('<x-badge round>Foo bar</x-badge>')
        ->assertSee('Foo bar')
        ->assertDontSee('rounded-md')
        ->assertSee('rounded-full');
});

it('can render size variations', function (array $size) {
    $key = array_key_first($size);
    $class = $size[$key];

    $component = <<<'HTML'
    <x-badge {{ size }}>Foo bar</x-badge>
    HTML;

    $component = str_replace('{{ size }}', $key, $component);

    $this->blade($component)
        ->assertSee('Foo bar')
        ->assertSee($class);
})->with([
    fn () => ['sm' => 'text-xs'],
    fn () => ['md' => 'text-sm'],
    fn () => ['lg' => 'text-md'],
]);

it('can render outline', function () {
    $this->blade('<x-badge outline>Foo bar</x-badge>')
        ->assertSee('Foo bar')
        ->assertSee('border-primary-500 text-primary-500');
});

it('can render icon on left', function () {
    $this->blade('<x-badge icon="users" position="left" text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500')
        ->assertSee('mr-1');
});

it('can render icon on right', function () {
    $this->blade('<x-badge icon="users" position="right" text="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-500')
        ->assertSee('ml-1');
});
