<?php

uses(Tests\TestCase::class)->group('Feature');

use Illuminate\Support\Facades\Route;
use Illuminate\View\ViewException;

afterEach(function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
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

it('can render with default id', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('command-palette:command-palette-open')
        ->toContain('command-palette:command-palette-close');
});

it('can render with custom id', function () {
    $component = <<<'HTML'
    <x-command-palette id="search-palette" request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('command-palette:search-palette-open')
        ->toContain('command-palette:search-palette-close');
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
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => true,
            'recycle' => true,
            'elements' => false,
            'scrollbar' => true,
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
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => false,
            'scrollbar' => true,
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

// --- Scrollbar tests ---

it('renders with custom scrollbar by default', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('command-palette-scrollbar');
});

it('renders without custom scrollbar when disabled', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => false,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->not->toContain('command-palette-scrollbar');
});

// --- Icon support tests ---

it('includes icon key in selectable', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id|icon:my_icon" />
    HTML;

    expect($component)->render()
        ->toContain('icon')
        ->toContain('my_icon');
});

it('includes default icon key in selectable', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('selectable.icon');
});

it('renders icon template in options', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('x-html="option[selectable.icon]"');
});

// --- Config fallback tests ---

it('can render with request from config as string url', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => 'https://example.com/global-search',
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
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
            'actionable' => null,
            'request' => ['url' => 'https://example.com/global-search', 'method' => 'post'],
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
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
            'actionable' => null,
            'request' => 'https://example.com/global-search',
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
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
            'actionable' => null,
            'request' => 'global.command.palette.search',
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
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

it('recycle is true by default', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.k',true,null,false,'command-palette')");
});

it('recycle can be disabled via config', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.k',false,null,false,'command-palette')");
});

it('inline recycle overrides config recycle', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => false,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" recycle />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.k',true,null,false,'command-palette')");
});

// --- Shortcut config tests ---

it('shortcut defaults to ctrl.k', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.k',");
});

it('shortcut can be changed via config', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.shift.p',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain("'ctrl.shift.p',");
});

it('inline shortcut overrides config shortcut', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.shift.p',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" shortcut="meta.k" />
    HTML;

    expect($component)->render()
        ->toContain("'meta.k',")
        ->not->toContain("'ctrl.shift.p',");
});

// --- Footer visibility tests ---

it('renders footer with x-show for conditional visibility', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('x-show="available.length > 0 || (search &')
        ->toContain('!loading &')
        ->toContain('fetched)"');
});

it('cannot use non-existent actionable class', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => 'App\\NonExistent\\FakeClass',
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] CommandPalette: The [actionable] class does not exist.');

    expect('<x-command-palette request="https://example.com/search" select="label:title|value:id" />')->render();
});

it('cannot use non-invocable actionable class', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => Tests\TestCase::class,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] CommandPalette: The [actionable] class must be invocable (__invoke).');

    expect('<x-command-palette request="https://example.com/search" select="label:title|value:id" />')->render();
});

it('renders with null actionable by default', function () {
    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('tallstackui_commandPalette');
});

it('passes action url when actionable is configured', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => Tests\Support\CommandPaletteActionableStub::class,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'persistent' => false,
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $component = <<<'HTML'
    <x-command-palette request="https://example.com/search" select="label:title|value:id" />
    HTML;

    expect($component)->render()
        ->toContain('command-palette\/action');
});
