<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render an empty list', function () {
    expect('<x-list />')->render()
        ->toContain('tallstackui_list')
        ->toContain('No items.');
});

it('can render with label and hint', function () {
    $component = <<<'HTML'
    <x-list label="Tags" hint="Manage your tags here." />
    HTML;

    expect($component)->render()
        ->toContain('Tags', 'Manage your tags here.');
});

it('can render with searchable input', function () {
    expect('<x-list searchable />')->render()
        ->toContain('tallstackui_list_search')
        ->toContain('placeholder="Search"');
});

it('renders with custom search placeholder', function () {
    expect('<x-list searchable search-placeholder="Filter tags" />')->render()
        ->toContain('placeholder="Filter tags"');
});

it('does not render search input by default', function () {
    expect('<x-list />')->render()
        ->not->toContain('tallstackui_list_search');
});

it('can render slot mode items without menu', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="general" caption="1 server" />
        <x-list.items name="production" caption="2 servers" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('general', '1 server', 'production', '2 servers')
        ->not->toContain('tallstackui_list_items_menu');
});

it('can render slot mode items with menu', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="general">
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
                <x-dropdown.items text="Delete" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('general')
        ->toContain('tallstackui_list_items_menu')
        ->toContain('Edit', 'Delete');
});

it('can render items with default slot content', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="production">
            <span class="custom-status-dot">active</span>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('production', 'custom-status-dot', 'active');
});

it('can render data-driven mode without menu', function () {
    $items = [
        ['name' => 'general', 'caption' => '1 server'],
        ['name' => 'production', 'caption' => '2 servers'],
    ];

    expect(Blade::render('<x-list :items="$items" />', compact('items')))
        ->toContain('general', '1 server', 'production', '2 servers')
        ->not->toContain('tallstackui_list_items_menu');
});

it('can render data-driven mode with @interact item_menu', function () {
    $items = [
        ['name' => 'general', 'id' => 1],
        ['name' => 'production', 'id' => 2],
    ];

    $component = <<<'BLADE'
    <x-list :items="$items">
        @interact('item_menu', $item)
            <x-dropdown.items text="Edit {{ $item['id'] }}" />
        @endinteract
    </x-list>
    BLADE;

    expect(Blade::render($component, compact('items')))
        ->toContain('general', 'production')
        ->toContain('Edit 1', 'Edit 2')
        ->toContain('tallstackui_list_items_menu');
});

it('accepts a Collection as items', function () {
    $items = collect([
        ['name' => 'general', 'caption' => '1 server'],
    ]);

    expect(Blade::render('<x-list :items="$items" />', compact('items')))
        ->toContain('general', '1 server');
});

it('renders the empty slot when provided', function () {
    $component = <<<'HTML'
    <x-list>
        <x-slot:empty>
            <p class="custom-empty">No tags configured.</p>
        </x-slot:empty>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('No tags configured.', 'custom-empty')
        ->not->toContain('No items.');
});

it('falls back to translation for empty state', function () {
    expect('<x-list />')->render()
        ->toContain('No items.');
});

it('applies max-h class when height is set', function () {
    expect('<x-list height="60" />')->render()
        ->toContain('max-h-60')
        ->toContain('overflow-y-auto');
});

it('does not apply max-h when height is not set', function () {
    expect('<x-list />')->render()
        ->not->toContain('max-h-')
        ->not->toContain('overflow-y-auto');
});

it('rejects invalid height token', function () {
    $this->expectException(ViewException::class);

    expect('<x-list height="invalid" />')->render();
});

it('rejects items missing name in data-driven mode', function () {
    $this->expectException(ViewException::class);

    $items = [
        ['name' => 'general'],
        ['caption' => 'no name here'],
    ];

    Blade::render('<x-list :items="$items" />', compact('items'));
});

it('rejects empty name on slot mode item', function () {
    $this->expectException(ViewException::class);

    expect('<x-list><x-list.items name="" /></x-list>')->render();
});

it('exposes data-list-row attributes for alpine filtering', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="general" caption="1 server" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('data-list-row')
        ->toContain('data-list-name="general"')
        ->toContain('data-list-caption="1 server"');
});

it('uses Alpine register() on item init', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="general" caption="1 server" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain("register('general', '1 server')");
});

it('renders the empty state with x-show binding tied to hasResults', function () {
    expect('<x-list />')->render()
        ->toContain('x-show="!hasResults"');
});

it('renders the search input with x-model bound to search', function () {
    expect('<x-list searchable />')->render()
        ->toContain('x-model.debounce.150ms="search"');
});

it('does not use divide-y on the box (avoids phantom dividers under last visible row)', function () {
    expect('<x-list />')->render()
        ->not->toContain('divide-y');
});

it('places inter-row dividers via an arbitrary adjacent-sibling selector on items.wrapper', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="a" />
        <x-list.items name="b" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('[&>[data-list-row]+[data-list-row]]:border-t');
});

it('renders a border-bottom on the search row when searchable', function () {
    expect('<x-list searchable />')->render()
        ->toContain('border-b');
});

it('does not render border-bottom from search row when searchable is false', function () {
    expect('<x-list />')->render()
        ->not->toContain('border-b');
});

it('uses an inline floating dropdown pinned at z-40', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="x">
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('z-40')
        ->not->toContain('z-50');
});

it('renders default size as sm and width as xxs on the items menu floating', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="x">
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('data-tsui-dropdown-size="sm"')
        ->toContain('data-tsui-dropdown-width="xxs"');
});

it('renders the chosen size flag on the items menu', function (string $size) {
    $component = <<<HTML
    <x-list>
        <x-list.items name="x" {$size}>
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain("data-tsui-dropdown-size=\"$size\"");
})->with(['xs', 'md', 'lg']);

it('keeps width at the xxs default regardless of size flag', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="x" md>
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('data-tsui-dropdown-size="md"')
        ->toContain('data-tsui-dropdown-width="xxs"');
});

it('respects width override on the items menu', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="x" width="2xl">
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('data-tsui-dropdown-width="2xl"');
});

it('rejects invalid width on the items menu', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-list>
        <x-list.items name="x" width="huge">
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render();
});

it('does not use the dropdown component for the items menu', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="x">
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->not->toContain('tallstackui_dropdown(')
        ->not->toContain('tallstackui_open_dropdown');
});

it('does not affect standalone dropdown when used outside list', function () {
    expect('<x-dropdown text="Menu"><x-dropdown.items text="A" /></x-dropdown>')->render()
        ->toContain('tallstackui_dropdown(')
        ->toContain('z-50')
        ->toContain('w-56');
});

it('applies content-visibility on each row to skip layout/paint of off-screen items', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="a" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('[content-visibility:auto]')
        ->toContain('[contain-intrinsic-size:auto_2.5rem]');
});

it('scopes the floating menu under list.items.menu for soft customization', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="a">
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot:menu>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toBeString()
        ->and(TallStackUi::customize('floating', scope: 'list.items.menu'))
        ->not->toBeNull();
});
