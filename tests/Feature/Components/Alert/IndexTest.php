<?php

use Illuminate\Support\Arr;
use Illuminate\View\ViewException;
use TasteUi\Facades\TasteUi;

it('can render title', function () {
    $this->blade('<x-alert title="Foo bar" />')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');
});

it('can render text', function () {
    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-300');
});

it('can render slot', function () {
    $this->blade('<x-alert>Foo bar</x-alert>')
        ->assertSee('Foo bar')
        ->assertSee('bg-primary-300');
});

it('can render closeable alert', function () {
    $this->blade('<x-alert text="Foo bar" closeable />')
        ->assertSee('<svg class="w-5 h-5 text-primary-900"', false);
});

it('can render translucent', function () {
    $this->blade('<x-alert text="Foo bar" translucent />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300')
        ->assertSee('bg-primary-100');
});

it('can render outline', function () {
    $this->blade('<x-alert text="Foo bar" outline />')
        ->assertSee('Foo bar')
        ->assertDontSee('bg-primary-300')
        ->assertSee('bg-primary-100')
        ->assertSee('border')
        ->assertSee('border-primary-900');
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

test('when translucent and white, it does not render translucent', function () {
    $this->blade('<x-alert text="Foo bar" color="white" translucent />')
        ->assertSee('Foo bar')
        ->assertSee('bg-white');
});

test('when outline and white, it does not render outline', function () {
    $this->blade('<x-alert text="Foo bar" color="white" outline />')
        ->assertSee('Foo bar')
        ->assertSee('bg-white');
});

it('can not render outline and translucent on the same component', function () {
    $this->blade('<x-alert text="Foo bar" outline translucent />');
})->throws(ViewException::class);

it('can personalize', function () {
    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-300')
        ->assertDontSee('rounded-full');

    TasteUi::personalize('alert')
        ->block('base', function (array $data) {
            return Arr::toCssClasses([
                'rounded-full p-4',
                TasteUi::colors()
                    ->set('bg', $data['color'], 300)
                    ->get() => ! $data['translucent'],
                TasteUi::colors()
                    ->set('bg', $data['color'], 100)
                    ->get() => $data['translucent'],
                TasteUi::colors()
                    ->set('border', $data['color'], 900)
                    ->get() => $data['outline'],
                'border' => $data['outline'],
            ]);
        });

    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-300')
        ->assertSee('rounded-full')
        ->assertDontSee('rounded-md');
});

it('cannot personalize wrong block', function () {
    $this->expectException(InvalidArgumentException::class);

    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-300')
        ->assertDontSee('rounded-full');

    TasteUi::personalize('alert')
        ->block('foo-bar', function (array $data) {
            return Arr::toCssClasses([
                'rounded-full p-4',
                TasteUi::colors()
                    ->set('bg', $data['color'], 300)
                    ->get() => ! $data['translucent'],
                TasteUi::colors()
                    ->set('bg', $data['color'], 100)
                    ->get() => $data['translucent'],
                TasteUi::colors()
                    ->set('border', $data['color'], 900)
                    ->get() => $data['outline'],
                'border' => $data['outline'],
            ]);
        });

    $this->blade('<x-alert text="Bar foo" />')
        ->assertSee('Bar foo')
        ->assertSee('bg-primary-300')
        ->assertSee('rounded-full')
        ->assertDontSee('rounded-md');
});
