<?php

uses(Tests\TestCase::class)->group('Feature');

use Illuminate\Support\Facades\Route;
use Illuminate\View\ViewException;

afterEach(function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
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

it('cannot render without request anywhere', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] CommandPalette: The [request] must be configured either as an inline attribute or in the config file');

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
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => true,
            'recycle' => false,
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
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
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
            'request' => null,
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
            'request' => null,
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

// --- Config fallback tests ---

it('can render with request from config as string url', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'request' => 'https://example.com/global-search',
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
            'elements' => true,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    expect('<x-command-palette />')->render()
        ->toContain('tallstackui_commandPalette')
        ->toContain('example.com');
});

it('can render with request from config as array', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'request' => ['url' => 'https://example.com/global-search', 'method' => 'post'],
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
            'elements' => true,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    expect('<x-command-palette />')->render()
        ->toContain('tallstackui_commandPalette')
        ->toContain('example.com');
});

it('inline request overrides config request', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'request' => 'https://example.com/global-search',
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
            'elements' => true,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/inline-search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('inline-search')
        ->not->toContain('global-search');
});

// --- Route name resolution tests ---

it('can resolve route name as request', function () {
    Route::get('/test-command-palette-search', fn () => [])->name('test.command.palette.search');

    $component = <<<'HTML'
    <x-command-palette request="test.command.palette.search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette')
        ->toContain('test-command-palette-search');
});

it('can resolve route name from config', function () {
    Route::get('/global-command-palette-search', fn () => [])->name('global.command.palette.search');

    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'request' => 'global.command.palette.search',
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
            'elements' => true,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    expect('<x-command-palette />')->render()
        ->toContain('tallstackui_commandPalette')
        ->toContain('global-command-palette-search');
});

it('treats unresolvable string as plain url', function () {
    $component = <<<'HTML'
    <x-command-palette request="/api/custom-search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette')
        ->toContain('custom-search');
});

// --- Recycle config tests ---

it('recycle is false by default', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.k',false)");
});

it('recycle can be enabled via config', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.k',true)");
});

it('inline recycle overrides config recycle', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
            'elements' => true,
            'scrollbar' => null,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" recycle />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.k',true)");
});
