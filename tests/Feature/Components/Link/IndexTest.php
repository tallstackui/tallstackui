<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $this->blade('<x-link href="https://google.com.br" />')
        ->assertSee('<a', false);
});

it('can render marked as blank', function () {
    $this->blade('<x-link href="https://google.com.br" blank />')
        ->assertSee('<a', false)
        ->assertSee('_blank');
});

it('can render marked as bold', function () {
    $this->blade('<x-link href="https://google.com.br" bold />')
        ->assertSee('<a', false)
        ->assertSee('font-bold');
});

it('can render with sizes', function (string $sizes) {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" {{ size }} />
    HTML;

    $component = str_replace('{{ size }}', $sizes, $component);

    $this->blade($component)
        ->assertSee('<a', false)
        ->assertSee('text-'.$sizes);
})->with([
    'sm' => ['sm'],
    'md' => ['md'],
    'lg' => ['lg'],
]);

it('can render with query string', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" :query="['foo' => 'bar']" />
    HTML;

    $this->blade($component)
        ->assertSee('<a', false)
        ->assertSee('?foo=bar', false);
});

it('can render with fragment', function () {
    $this->blade('<x-link href="https://google.com.br" fragment="foo-bar-baz" />')
        ->assertSee('<a', false)
        ->assertSee('foo-bar-baz');
});

it('can render with query string and fragment', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" :query="['foo' => 'bar']" fragment="foo-bar-baz" />
    HTML;

    $this->blade($component)
        ->assertSee('<a', false)
        ->assertSee('?foo=bar', false)
        ->assertSee('#foo-bar-baz', false);
});

it('can render with icons', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" icon="users" position="right" />
    HTML;

    $this->blade($component)
        ->assertSee('<a', false)
        ->assertSee('<svg', false);
});

it('can render with color', function (string $colors) {
    $component = <<<HTML
    <x-link href="https://google.com.br" color="$colors" />
    HTML;

    $color = match ($colors) {
        'white' => 'text-white',
        'black' => 'text-black',
        default => "text-$colors-500",
    };

    $this->blade($component)
        ->assertSee('<a', false)
        ->assertSee($color, false);
})->with('colors');

it('can render without color', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" :color="null" />
    HTML;

    $this->blade($component)
        ->assertSee('<a', false);
});

it('cannot render without href', function () {
    $this->expectException(ViewException::class);

    $this->blade('<x-link />')
        ->assertSee('<a', false);
});
