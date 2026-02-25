<?php

uses(Tests\TestCase::class)->group('Feature');

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

it('can render with loading')
    ->expect('<x-card loading="save">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('wire:loading')
    ->toContain('wire:target="save"');

it('can render with loading and delay')
    ->expect('<x-card loading="save" delay="longest">Foo bar</x-card>')
    ->render()
    ->toContain('wire:loading.delay.longest')
    ->toContain('wire:target="save"');

it('does not render loading bar by default')
    ->expect('<x-card>Foo bar</x-card>')
    ->render()
    ->not->toContain('wire:loading');

it('adds relative class when loading is set')
    ->expect('<x-card loading="save">Foo bar</x-card>')
    ->render()
    ->toContain('relative');

it('renders loading bar with indeterminate animation')
    ->expect('<x-card loading="save">Foo bar</x-card>')
    ->render()
    ->toContain('animate-indeterminate');

it('can render with event listeners on wrapper')
    ->expect('<x-card header="Test" minimize x-on:minimize="handleMinimize" x-on:maximize="handleMaximize">Foo bar</x-card>')
    ->render()
    ->toContain('x-on:minimize="handleMinimize"')
    ->toContain('x-on:maximize="handleMaximize"');

it('cannot use image and color together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Card: The [image] and [color] cannot be used together.');

    expect('<x-card image="https://via.placeholder.com/150" color="red">Foo bar</x-card>')
        ->render();
});
