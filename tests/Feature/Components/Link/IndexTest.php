<?php

use Illuminate\View\ViewException;

it('can render')
    ->expect('<x-link href="https://google.com.br" />')
    ->render()
    ->toContain('<a href="https://google.com.br"');

it('can render marked as blank')
    ->expect('<x-link href="https://google.com.br" blank />')
    ->render()
    ->toContain('<a href="https://google.com.br"')
    ->toContain('_blank');

it('can render marked as bold')
    ->expect('<x-link href="https://google.com.br" bold />')
    ->render()
    ->toContain('<a')
    ->toContain('font-bold');

it('can render with sizes', function (string $sizes) {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" {{ size }} />
    HTML;

    $component = str_replace('{{ size }}', $sizes, $component);

    expect($component)->render()
        ->toContain('<a')
        ->toContain('text-'.$sizes);
})->with([
    'sm' => ['sm'],
    'md' => ['md'],
    'lg' => ['lg'],
]);

it('can render with query string', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br/" :query="['foo' => 'bar']" />
    HTML;

    expect($component)->render()
        ->toContain('<a')
        ->toContain('?foo=bar');
});

it('can render with fragment')
    ->expect('<x-link href="https://google.com.br" fragment="foo-bar-baz" />')->render()
    ->toContain('<a')
    ->toContain('#foo-bar-baz');

it('can render with fragment and without href')
    ->expect('<x-link fragment="foo-bar-baz" />')->render()
    ->toContain('<a')
    ->toContain('foo-bar-baz');

it('can render with query string and fragment', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" :query="['foo' => 'bar']" fragment="foo-bar-baz" />
    HTML;

    expect($component)->render()
        ->toContain('<a href="https://google.com.br?foo=bar#foo-bar-baz"');
});

it('can render with icons', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" icon="users" position="right" />
    HTML;

    expect($component)->render()
        ->toContain('<a href="https://google.com.br"')
        ->toContain('<svg');
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

    expect($component)->render()
        ->toContain('<a href="https://google.com.br"')
        ->toContain($color);
})->with('colors');

it('can render without color', function () {
    $component = <<<'HTML'
    <x-link href="https://google.com.br" :color="null" />
    HTML;

    expect($component)->render()
        ->toContain('<a href="https://google.com.br"')
        ->not->toContain('text-primary-500');

    $component = <<<'HTML'
    <x-link href="https://google.com.br" colorless />
    HTML;

    expect($component)->render()
        ->toContain('<a href="https://google.com.br"')
        ->not->toContain('text-primary-500');
});

it('can render with wire:navigate')
    ->expect('<x-link href="https://google.com.br" navigate />')
    ->render()
    ->toContain('<a href="https://google.com.br"')
    ->toContain('wire:navigate');

it('can render with wire:navigate.hover')
    ->expect('<x-link href="https://google.com.br" navigate-hover />')
    ->render()
    ->toContain('<a href="https://google.com.br"')
    ->toContain('wire:navigate.hover');

it('cannot render without href', function () {
    $this->expectException(ViewException::class);

    expect('<x-link />')->render()
        ->toContain('<a');
});
