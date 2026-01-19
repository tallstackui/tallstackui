<?php

use Illuminate\View\ViewException;

it('can render')
    ->expect('<x-banner text="Foo bar" />')
    ->render()
    ->toContain('Foo bar');

it('can render with custom colors', function () {
    $component = <<<'HTML'
    <x-banner text="Foo bar" :color="[
        'background' => 'bg-[#000000]',
        'text' => 'text-[#ffffff]',
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('bg-[#000000]')
        ->toContain('text-[#ffffff]');
});

it('can render with sizes', function (string $size) {
    $component = <<<HTML
    <x-banner text="Foo bar" size="$size" />
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
})->with(['sm', 'md', 'lg']);

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-banner text="Foo bar" color="$colors" />
    HTML;

    expect($component)->render()
        ->toContain('Foo bar');
})->with('colors');

it('can render with multiple text as array', function () {
    $component = <<<'HTML'
    <x-banner :text="['Foo']" />
    HTML;

    expect($component)->render()
        ->toContain('Foo');
});

it('can render with text as collection', function () {
    $collect = collect(['Foo']);

    $component = <<<HTML
    <x-banner :text="$collect" />
    HTML;

    expect($component)->render()
        ->toContain('Foo');
});

it('can render date until as string', function () {
    $date = now()->addDay()->format('Y-m-d');

    $component = <<<HTML
    <x-banner text="Foo" until="$date" />
    HTML;

    expect($component)->render()
        ->toContain('Foo');
});

it('can render date until as carbon instance', function () {
    $component = <<<'HTML'
    <x-banner text="Foo" :until="now()->addDay()" />
    HTML;

    expect($component)->render()
        ->toContain('Foo');
});

it('can render animated', function () {
    $component = <<<'HTML'
    <x-banner text="Foo" animated :enter="null" leave="5" />
    HTML;

    expect($component)->render()
        ->toContain('Foo');
});

it('cannot render with date in past', function (string $date) {
    $component = <<<HTML
    <x-banner text="Foo bar baz" until="$date" />
    HTML;

    expect($component)->render()
        ->not->toContain('Foo bar baz');
})->with([
    fn () => now()->subDay()->format('Y-m-d'),
    fn () => now()->subWeek()->format('Y-m-d'),
]);

it('cannot render with custom without background', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-banner text="Foo bar" :color="[
        'text' => 'text-[#ffffff]',
    ]" />
    HTML;

    expect($component)->render();
});

it('cannot render with custom without text', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-banner text="Foo bar" :color="[
        'background' => 'bg-[#ffffff]',
    ]" />
    HTML;

    expect($component)->render();
});
