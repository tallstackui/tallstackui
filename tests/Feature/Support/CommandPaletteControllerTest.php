<?php

use Illuminate\Support\Facades\URL;

beforeEach(function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => Tests\Support\CommandPaletteActionableStub::class,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);
});

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
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);
});

it('returns 403 without valid signature', function () {
    $this->postJson('/tallstackui/command-palette/action', [
        'item' => ['label' => 'Test', 'value' => 1],
        'search' => 'test',
    ])->assertStatus(403);
});

it('processes actionable with valid signed request', function () {
    $url = URL::signedRoute('tallstackui.command-palette.action');

    $this->postJson($url, [
        'item' => ['label' => 'Test', 'value' => 1],
        'search' => 'test',
    ])->assertOk()
        ->assertJson([
            'type' => 'redirect',
            'data' => ['to' => '/test'],
            'external' => false,
            'navigate' => false,
        ]);
});

it('returns 404 when actionable is not configured', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => null,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $url = URL::signedRoute('tallstackui.command-palette.action');

    $this->postJson($url, [
        'item' => ['label' => 'Test', 'value' => 1],
        'search' => 'test',
    ])->assertStatus(404);
});

it('returns 422 when actionable is not invocable', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => Tests\Support\CommandPaletteNonInvocableStub::class,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $url = URL::signedRoute('tallstackui.command-palette.action');

    $this->postJson($url, [
        'item' => ['label' => 'Test', 'value' => 1],
        'search' => 'test',
    ])->assertStatus(422);
});

it('passes search term to item selected', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => Tests\Support\CommandPaletteActionableWithSearchStub::class,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $url = URL::signedRoute('tallstackui.command-palette.action');

    $this->postJson($url, [
        'item' => ['label' => 'Test', 'value' => 1],
        'search' => 'my-search-term',
    ])->assertOk()
        ->assertJson([
            'type' => 'event',
            'data' => [
                'name' => 'searched',
                'params' => ['term' => 'my-search-term'],
            ],
        ]);
});

it('returns navigate flag when callback uses navigate', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => Tests\Support\CommandPaletteActionableWithNavigateStub::class,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $url = URL::signedRoute('tallstackui.command-palette.action');

    $this->postJson($url, [
        'item' => ['label' => 'Test', 'value' => 1],
        'search' => 'test',
    ])->assertOk()
        ->assertJson([
            'type' => 'redirect',
            'data' => ['to' => '/test'],
            'external' => false,
            'navigate' => true,
        ]);
});

it('passes additional data to item selected', function () {
    config()->set('ts-ui.components.command-palette', [
        TallStackUi\Components\CommandPalette\Component::class,
        [
            'actionable' => Tests\Support\CommandPaletteActionableWithAdditionalStub::class,
            'request' => null,
            'z-index' => 'z-50',
            'blur' => false,
            'overflow' => false,
            'shortcut' => 'ctrl.k',
            'recycle' => true,
            'elements' => true,
            'scrollbar' => true,
        ],
    ]);

    __ts_get_component_configuration(TallStackUi\Components\CommandPalette\Component::class, flush: true);

    $url = URL::signedRoute('tallstackui.command-palette.action');

    $this->postJson($url, [
        'item' => [
            'label' => 'Test',
            'value' => 1,
            'additional' => ['role' => 'admin'],
        ],
        'search' => 'test',
    ])->assertOk()
        ->assertJson([
            'type' => 'event',
            'data' => [
                'name' => 'role-check',
                'params' => ['role' => 'admin'],
            ],
        ]);
});
