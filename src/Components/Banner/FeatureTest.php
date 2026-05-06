<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

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
})->with(colorsDataset());

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

it('can render with rotate boolean defaulting to normal speed', function () {
    $component = <<<'HTML'
    <x-banner rotate text="Rolling text" />
    HTML;

    expect($component)->render()
        ->toContain('Rolling text')
        ->toContain('motion-safe:animate-banner-rotate-normal')
        ->toContain('[container-type:inline-size]');
});

it('can render with rotate speed keywords', function (string $speed) {
    $component = <<<HTML
    <x-banner rotate="$speed" text="Foo" />
    HTML;

    expect($component)->render()
        ->toContain('motion-safe:animate-banner-rotate-'.$speed);
})->with(['slow', 'normal', 'fast']);

it('can render rotate with array of texts joined by separator', function () {
    $component = <<<'HTML'
    <x-banner rotate :text="['Foo', 'Bar', 'Baz']" />
    HTML;

    expect($component)->render()
        ->toContain('Foo • Bar • Baz');
});

it('can render rotate with array and custom separator', function () {
    $component = <<<'HTML'
    <x-banner rotate :text="['Foo', 'Bar']" separator=" — " />
    HTML;

    expect($component)->render()
        ->toContain('Foo — Bar');
});

it('preserves random pick on array when rotate is off', function () {
    $component = <<<'HTML'
    <x-banner :text="['OnlyOne']" />
    HTML;

    expect($component)->render()
        ->toContain('OnlyOne')
        ->not->toContain('motion-safe:animate-banner-rotate');
});

it('cannot use rotate together with wire', function () {
    $this->expectException(ViewException::class);

    expect('<x-banner rotate wire />')->render();
});

it('cannot use rotate with invalid string', function () {
    $this->expectException(ViewException::class);

    expect('<x-banner rotate="ultrafast" text="Foo" />')->render();
});

it('reserves left spacing on rotate viewport when left slot is present', function () {
    $component = <<<'HTML'
    <x-banner rotate text="Rolling">
        <x-slot:left>NEW</x-slot>
    </x-banner>
    HTML;

    expect($component)->render()
        ->toContain('ml-12');
});

it('reserves right spacing on rotate viewport when close button is present', function () {
    $component = <<<'HTML'
    <x-banner rotate close text="Rolling" />
    HTML;

    expect($component)->render()
        ->toContain('mr-8');
});

it('does not reserve lateral spacing on rotate viewport when no slot or close', function () {
    $component = <<<'HTML'
    <x-banner rotate text="Rolling" />
    HTML;

    expect($component)->render()
        ->not->toContain('ml-12')
        ->not->toContain('mr-8');
});

it('applies an edge fade mask on the rotate viewport', function () {
    $component = <<<'HTML'
    <x-banner rotate text="Rolling" />
    HTML;

    expect($component)->render()
        ->toContain('mask-image:linear-gradient');
});
