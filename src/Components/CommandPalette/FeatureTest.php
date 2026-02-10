<?php

uses(Tests\TestCase::class)->group('Feature');

use Illuminate\View\ViewException;

it('can render')
    ->expect('<x-command-palette />')
    ->render()
    ->toContain('tallstackui_commandPalette');

it('can render with static options', function () {
    $component = <<<'HTML'
    <x-command-palette :options="[
        ['label' => 'Settings', 'value' => 'settings'],
        ['label' => 'Profile', 'value' => 'profile'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('can render with request', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette')
        ->toContain('example.com\/search');
});

it('can render with array request', function () {
    $component = <<<'HTML'
    <x-command-palette :request="['url' => 'https://example.com/search', 'method' => 'post']" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('cannot use options and request together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] CommandPalette: The [options] and [request] cannot be defined at the same time.');

    $component = <<<'HTML'
    <x-command-palette :options="[['label' => 'Foo', 'value' => 'foo']]"
                       request="https://example.com/search"
                       select="label:label|value:value" />
    HTML;

    expect($component)->render();
});

it('cannot use invalid method in request array', function (string $method) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] CommandPalette: The attribute [method] must be "get" or "post".');

    $component = <<<'HTML'
    <x-command-palette :request="[
        'url' => 'https://example.com/search',
        'method' => '{{ method }}',
    ]" select="label:title|value:id" />
    HTML;

    $component = str_replace('{{ method }}', $method, $component);

    expect($component)->render();
})->with(['delete', 'put', 'patch']);

it('cannot use request array without url', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] CommandPalette: The attribute [url] is required in the request array.');

    $component = <<<'HTML'
    <x-command-palette :request="['method' => 'get']" select="label:title|value:id" />
    HTML;

    expect($component)->render();
});

it('can render with custom select string', function () {
    $component = <<<'HTML'
    <x-command-palette :options="[
        ['name' => 'Settings', 'id' => 1, 'desc' => 'App settings'],
    ]" select="label:name|value:id|description:desc" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('can render with options that have images and descriptions', function () {
    $component = <<<'HTML'
    <x-command-palette :options="[
        ['label' => 'John', 'value' => 1, 'description' => 'Engineer', 'image' => 'https://example.com/avatar.jpg'],
    ]" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('can render with empty slot', function () {
    $component = <<<'HTML'
    <x-command-palette>
        <x-slot:empty>
            <p>Custom empty state</p>
        </x-slot:empty>
    </x-command-palette>
    HTML;

    expect($component)->render()
        ->toContain('Custom empty state');
});

it('can render with default empty message')
    ->expect('<x-command-palette />')
    ->render()
    ->toContain('No results found.');

it('renders search input')
    ->expect('<x-command-palette />')
    ->render()
    ->toContain('tallstackui_command_palette_search');

it('renders keyboard hints')
    ->expect('<x-command-palette />')
    ->render()
    ->toContain('navigate')
    ->toContain('select')
    ->toContain('close');
