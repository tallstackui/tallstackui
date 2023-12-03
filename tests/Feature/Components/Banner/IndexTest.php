<?php

it('can render', function () {
    $this->blade('<x-banner text="Foo bar" />')
        ->assertSee('Foo bar');
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

it('can render date until as string', function () {
    $date = now()->addDay()->format('Y-m-d');

    $component = <<<HTML
    <x-banner text="Foo" :until="$date" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('can render date until as carbon instance', function () {
    $date = now()->addDay();

    $component = <<<HTML
    <x-banner text="Foo" :until="$date" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
})->skip('Unable to pass carbon instance as attribute');

it('can render animated', function () {
    $component = <<<'HTML'
    <x-banner text="Foo" animated :enter="null" leave="5" />
    HTML;

    $this->blade($component)
        ->assertSee('Foo');
});

it('cannot render with date in past', function (string $date) {
    $component = <<<HTML
    <x-banner text="Foo bar baz" until="$date" />
    HTML;

    $this->blade($component)
        ->assertDontSee('Foo bar baz');
})->with([
    fn () => now()->subDay()->format('Y-m-d'),
    fn () => now()->subWeek()->format('Y-m-d'),
]);
