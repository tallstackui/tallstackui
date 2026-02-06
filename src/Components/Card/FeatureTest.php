<?php

uses(Tests\TestCase::class);

use Illuminate\View\ViewException;

it('can render')
    ->expect('<x-card>Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar');

it('can render with title')
    ->expect('<x-card title="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Bar Baz');

it('can render with footer')
    ->expect('<x-card footer="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Bar Baz');

it('can render with title and footer')
    ->expect('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Lorem Ipsum')
    ->toContain('Bar Baz');

it('can render with image')
    ->expect('<x-card image="https://via.placeholder.com/150">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('https://via.placeholder.com/150');

it('cannot use image and color together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Card: The [image] and [color] cannot be used together.');

    expect('<x-card image="https://via.placeholder.com/150" color="red">Foo bar</x-card>')
        ->render();
});
