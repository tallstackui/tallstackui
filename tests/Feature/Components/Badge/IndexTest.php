<?php

use Illuminate\Support\Arr;
use TasteUi\Facades\TasteUi;

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

it('can personalize', function () {
    $this->blade('<x-badge text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-500')
        ->assertDontSee('px-4');

    TasteUi::personalize()
        ->badge()
        ->base(function (array $data) {
            return Arr::toCssClasses([
                'outline-none inline-flex items-center border px-4 py-0.5 font-bold',
                'text-xs' => $data['size'] === 'sm',
                'text-sm' => $data['size'] === 'md',
                'text-md' => $data['size'] === 'lg',
                'text-white' => $data['style'] === 'solid',
                'rounded-md' => $data['square'] === null,
                TasteUi::colors()
                    ->set('border', $data['color'], 500)
                    ->mergeWhen($data['style'] === 'solid', 'bg', $data['color'], 500)
                    ->get(),
                TasteUi::colors()
                    ->set('text', $data['color'], 500)
                    ->get() => $data['style'] === 'outline',
            ]);
        });

    $this->blade('<x-badge text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-500')
        ->assertSee('px-4')
        ->assertDontSee('px-2');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    $this->blade('<x-badge text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-500')
        ->assertDontSee('px-4');

    TasteUi::personalize()
        ->badge()
        ->wrong(function (array $data) {
            return Arr::toCssClasses([
                'outline-none inline-flex items-center border px-4 py-0.5 font-bold',
                'text-xs' => $data['size'] === 'sm',
                'text-sm' => $data['size'] === 'md',
                'text-md' => $data['size'] === 'lg',
                'text-white' => $data['style'] === 'solid',
                'rounded-md' => $data['square'] === null,
                TasteUi::colors()
                    ->set('border', $data['color'], 500)
                    ->mergeWhen($data['style'] === 'solid', 'bg', $data['color'], 500)
                    ->get(),
                TasteUi::colors()
                    ->set('text', $data['color'], 500)
                    ->get() => $data['style'] === 'outline',
            ]);
        });

    $this->blade('<x-badge text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-500')
        ->assertSee('px-4')
        ->assertDontSee('px-2');
});

it('can personalize all blocks available', function () {
    $this->blade('<x-badge text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-500')
        ->assertDontSee('px-4');

    TasteUi::personalize()
        ->badge()
        ->base('justify-between')
        ->icon('w-4 h-4');

    $this->blade('<x-badge text="Bar foo" icon="user" />')
        ->assertSee('Bar foo')
        ->assertSee('justify-between')
        ->assertSee('w-4 h-4');
});
