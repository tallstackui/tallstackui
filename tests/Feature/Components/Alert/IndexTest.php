<?php

it('can render title', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600');
});

it('can render text', function () {
    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-600');
});

it('can render slot', function () {
    $this->blade('<x-alert>Foo bar</x-alert>')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-600');
});

it('can render with footer slot', function () {
    $component = <<<'HTML'
        <x-alert>
            Foo bar
            <x-slot:footer>
                <button>Button</button>
            </x-slot:footer>
        </x-alert>
    HTML;

    $this->blade($component)
        ->assertSee('Foo bar')
        ->assertSee('Button');
});

it('can render close alert', function () {
    $this->blade('<x-alert text="Foo bar" close />')
        ->assertSee('<svg class="w-5 h-5 text-primary-50"', false);
});

it('can render light', function () {
    $this->blade('<x-alert text="Foo bar" light />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-600')
        ->assertSee('bg-primary-50');
});

it('can render black background with white text', function () {
    $this->blade('<x-alert text="Foo bar" color="black" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-black')
        ->assertSee('text-white')
        ->assertDontSee('text-black');
});

it('can render white background with black text', function () {
    $this->blade('<x-alert text="Foo bar" color="white" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-white')
        ->assertSee('text-black')
        ->assertDontSee('text-white');
});
