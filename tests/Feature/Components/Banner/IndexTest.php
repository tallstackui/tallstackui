<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $this->blade('<x-banner text="Foo bar" />')
        ->assertSee('Foo bar');
});

it('can render with custom colors', function () {
    $component = <<<'HTML'
    <x-banner text="Foo bar" :color="[
        'background' => 'bg-[#000000]',
        'text' => 'text-[#ffffff]',
    ]" />
    HTML;

    $this->blade($component)
        ->assertSee('bg-[#000000]')
        ->assertSee('text-[#ffffff]');
});

it('can render with sizes', function (string $size) {
    $component = <<<HTML
    <x-banner text="Foo bar" size="$size" />
    HTML;

    $this->blade($component)->assertSee('Foo bar');
})->with(['sm', 'md', 'lg']);

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-banner text="Foo bar" color="$colors" />
    HTML;

    $this->blade($component)->assertSee('Foo bar');
})->with('colors');

it('can render with multiple text as array', function () {
    $component = <<<'HTML'
    <x-banner :text="['Foo']" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('can render with text as collection', function () {
    $collect = collect(['Foo']);

    $component = <<<HTML
    <x-banner :text="$collect" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('can render date start as string', function () {
    $date = now()->addDay()->format('Y-m-d');

    $component = <<<HTML
    <x-banner text="Foo" start="$date" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Foo');
});

it('can render date start as carbon instance', function () {
    $component = <<<'HTML'
    <x-banner text="Foo" :start="now()->addDay()" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Foo');
});

it('can render when start', function () {
    $now = now();
    $date = $now->addWeek()->format('Y-m-d');

    $component = <<<HTML
    <x-banner text="Foo" start="$date" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Foo');

    test()->travelTo($now);

    $component = <<<HTML
    <x-banner text="Foo" start="$date" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('cannot render when date is over', function () {
    $now = now();
    $date = $now->format('Y-m-d');

    $component = <<<HTML
    <x-banner text="Foo Bar Baz" until="$date" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo Bar Baz');

    test()->travelTo($now->addDay());

    $component = <<<HTML
    <x-banner text="Baz Bar Foo" until="$date" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Baz Bar Foo');
});

it('can render between start and until', function () {
    $now = now();
    $start = $now->format('Y-m-d');
    $until = $now->addDay()->format('Y-m-d');

    $component = <<<HTML
    <x-banner text="Foo Bar Baz" start="$start" until="$until" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo Bar Baz');

    test()->travelTo($now->addWeek());

    $component = <<<HTML
    <x-banner text="Baz Bar Foo" start="$start" until="$until" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Baz Bar Foo');
});

it('can render date until as string', function () {
    $date = now()->addDay()->format('Y-m-d');

    $component = <<<HTML
    <x-banner text="Foo" until="$date" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('can render date until as carbon instance', function () {
    $component = <<<'HTML'
    <x-banner text="Foo" :until="now()->addDay()" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('can render animated', function () {
    $component = <<<'HTML'
    <x-banner text="Foo" animated :enter="null" leave="5" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('cannot render with until date in past', function (string $date) {
    $component = <<<HTML
    <x-banner text="Foo bar baz" until="$date" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Foo bar baz');
})->with([
    fn () => now()->subDay()->format('Y-m-d'),
    fn () => now()->subWeek()->format('Y-m-d'),
]);

it('cannot render with start date in future', function (string $date) {
    $component = <<<HTML
    <x-banner text="Foo bar baz" start="$date" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Foo bar baz');
})->with([
    fn () => now()->addDay()->format('Y-m-d'),
    fn () => now()->addWeek()->format('Y-m-d'),
]);

it('cannot render with custom without background', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-banner text="Foo bar" :color="[
        'text' => 'text-[#ffffff]',
    ]" />
    HTML;

    $this->blade($component);
});

it('cannot render with custom without text', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-banner text="Foo bar" :color="[
        'background' => 'bg-[#ffffff]',
    ]" />
    HTML;

    $this->blade($component);
});
