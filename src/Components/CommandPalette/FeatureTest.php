<?php

uses(Tests\TestCase::class)->group('Feature');

use Illuminate\View\ViewException;

afterEach(function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'elements' => true,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);
});

it('can render', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('cannot render without request', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] CommandPalette: The [request] attribute is required.');

    expect('<x-command-palette />')->render();
});

it('can render with array request', function () {
    $component = <<<'HTML'
    <x-command-palette :request="['url' => 'https://example.com/search', 'method' => 'post']" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
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
    <x-command-palette request="https://example.com/search" select="label:name|value:id|description:desc" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('can render with empty slot', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id">
        <x-slot:empty>
            <p>Custom empty state</p>
        </x-slot:empty>
    </x-command-palette>
    HTML;

    expect($component)->render()
        ->toContain('Custom empty state');
});

it('can render with default empty message', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('No results found.');
});

it('renders search input', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_command_palette_search');
});

it('renders keyboard hints', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('↑↓')
        ->toContain('↵')
        ->toContain('esc');
});

it('can render with recycle prop', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" recycle />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('renders with click-outside close by default', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('x-on:click.self="close()"');
});

it('renders without click-outside close when persistent', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => true,
            'elements' => false,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->not->toContain('x-on:click.self="close()"');
});

it('shows keyboard hints by default', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('↑↓')
        ->toContain('↵');
});

it('hides keyboard hints when elements config is false', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'elements' => false,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->not->toContain('↑↓')
        ->not->toContain('↵');
});

it('renders with soft scrollbar', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'elements' => false,
            'scrollbar' => 'soft',
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('soft-scrollbar');
});

it('renders with custom scrollbar', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'elements' => false,
            'scrollbar' => 'custom',
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('custom-scrollbar');
});

it('renders without scrollbar class by default', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->not->toContain('soft-scrollbar')
        ->not->toContain('custom-scrollbar');
});
