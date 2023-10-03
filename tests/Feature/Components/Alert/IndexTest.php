<?php

use Illuminate\Support\Arr;
use TasteUi\Facades\TasteUi;

it('can render title', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-400');
});

it('can render text', function () {
    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-400');
});

it('can render slot', function () {
    $this->blade('<x-alert>Foo bar</x-alert>')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-400');
});

it('can render be closeable', function () {
    $this->blade('<x-alert text="Foo bar" closeable />')
        ->assertSee('<svg class="w-5 h-5 text-primary-800"', false);
});

it('can render translucent', function () {
    $this->blade('<x-alert text="Foo bar" translucent />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-400')
        ->assertSee('bg-primary-100');
});

it('can personalize', function () {
    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-400')
        ->assertDontSee('rounded-full');

    TasteUi::personalize()
        ->alert()
        ->base(function (array $data) {
            return Arr::toCssClasses([
                'rounded-full p-4',
                TasteUi::colors()
                    ->set('bg', $data['color'], 400)
                    ->get() => ! $data['translucent'],
                TasteUi::colors()
                    ->set('bg', $data['color'], 100)
                    ->get() => $data['translucent'],
            ]);
        });

    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-400')
        ->assertSee('rounded-full')
        ->assertDontSee('rounded-md');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-400')
        ->assertDontSee('rounded-full');

    TasteUi::personalize()
        ->alert()
        ->wrong(function (array $data) {
            return Arr::toCssClasses([
                'rounded-full p-4',
                TasteUi::colors()
                    ->set('bg', $data['color'], 400)
                    ->get() => ! $data['translucent'],
                TasteUi::colors()
                    ->set('bg', $data['color'], 100)
                    ->get() => $data['translucent'],
            ]);
        });

    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-400')
        ->assertSee('rounded-full')
        ->assertDontSee('rounded-md');
});