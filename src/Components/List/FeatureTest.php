<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use TallStackUi\Facades\TallStackUi;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

/**
 * The lazy payload reaches the browser through `@js()`, which hex-escapes the
 * quotes for the JS lexer to resolve before `JSON.parse` ever sees them.
 */
function list_lazy_payload(string $html): array
{
    preg_match("/tallstackui_list\(JSON\.parse\('(.*?)'\)\)/", $html, $matches);

    $json = preg_replace_callback(
        '/\\\\u([0-9a-fA-F]{4})/',
        fn (array $escape): string => (string) mb_chr((int) hexdec($escape[1])),
        $matches[1] ?? ''
    );

    return (array) json_decode((string) $json, true);
}

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

it('places inter-row dividers via a general-sibling selector keyed on the visibility marker', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="a" />
        <x-list.items name="b" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('[&>[data-list-on]~[data-list-on]]:border-t');
});

it('does not key inter-row dividers on the adjacent-sibling combinator', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="a" />
        <x-list.items name="b" />
    </x-list>
    HTML;

    expect($component)->render()
        ->not->toContain('[data-list-row]+[data-list-row]');
});

it('marks every row with the visibility attribute bound to the search match', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="general" caption="1 server" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('data-list-on')
        ->toContain("x-bind:data-list-on=\"match('general', '1 server')\"");
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

it('renders raw markup coming from the caption slot', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="production">
            <x-slot:caption>
                <span class="custom-caption">2 servers</span>
            </x-slot>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('<span class="custom-caption">2 servers</span>');
});

it('keeps escaping the caption when it comes as a plain attribute', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="production" caption="<b>2</b> servers" />
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('&lt;b&gt;2&lt;/b&gt; servers')
        ->not->toContain('<b>2</b> servers');
});

it('feeds the alpine search with the plain text of a caption slot', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="production">
            <x-slot:caption>
                <span class="custom-caption">2 servers</span>
            </x-slot>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('data-list-caption="2 servers"')
        ->toContain("register('production', '2 servers')");
});

it('renders raw markup coming from the action slot without the dropdown chrome', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="production">
            <x-slot:action>
                <button class="custom-action">Deploy</button>
            </x-slot>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('<button class="custom-action">Deploy</button>')
        ->not->toContain('tallstackui_list_items_menu');
});

it('renders the action and the menu side by side', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="production">
            <x-slot:action>
                <button class="custom-action">Deploy</button>
            </x-slot>
            <x-slot:menu>
                <x-dropdown.items text="Edit" />
            </x-slot>
        </x-list.items>
    </x-list>
    HTML;

    expect($component)->render()
        ->toContain('custom-action')
        ->toContain('tallstackui_list_items_menu')
        ->toContain('flex shrink-0 items-center gap-x-2');
});

it('does not render the aside wrapper when there is no action nor menu', function () {
    $component = <<<'HTML'
    <x-list>
        <x-list.items name="production" caption="2 servers" />
    </x-list>
    HTML;

    expect($component)->render()
        ->not->toContain('flex shrink-0 items-center gap-x-2');
});

it('can render data-driven mode with @interact item_caption', function () {
    $items = [
        ['name' => 'general', 'caption' => '1 server'],
        ['name' => 'production', 'caption' => '2 servers'],
    ];

    $component = <<<'BLADE'
    <x-list :items="$items">
        @interact('item_caption', $item)
            <span class="custom-caption">{{ $item['caption'] }}</span>
        @endinteract
    </x-list>
    BLADE;

    expect(Blade::render($component, compact('items')))
        ->toContain('<span class="custom-caption">1 server</span>')
        ->toContain('<span class="custom-caption">2 servers</span>')
        ->toContain('data-list-caption="1 server"');
});

it('can render data-driven mode with @interact item_action', function () {
    $items = [
        ['name' => 'general', 'id' => 1],
        ['name' => 'production', 'id' => 2],
    ];

    $component = <<<'BLADE'
    <x-list :items="$items">
        @interact('item_action', $item)
            <button class="custom-action">Go {{ $item['id'] }}</button>
        @endinteract
    </x-list>
    BLADE;

    expect(Blade::render($component, compact('items')))
        ->toContain('<button class="custom-action">Go 1</button>')
        ->toContain('<button class="custom-action">Go 2</button>')
        ->not->toContain('tallstackui_list_items_menu');
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

it('can render the lazy mode serializing the items into the alpine payload', function () {
    $items = [
        ['name' => 'general', 'caption' => '1 server'],
        ['name' => 'production', 'caption' => '2 servers'],
    ];

    $payload = list_lazy_payload(Blade::render('<x-list :items="$items" height="60" lazy />', compact('items')));

    expect($payload)->toBe([
        'chunk' => 20,
        'items' => [
            ['name' => 'general', 'caption' => '1 server'],
            ['name' => 'production', 'caption' => '2 servers'],
        ],
    ]);
});

it('can render the lazy mode with a custom slice', function () {
    $items = [['name' => 'general']];

    $payload = list_lazy_payload(Blade::render('<x-list :items="$items" height="60" lazy="5" />', compact('items')));

    expect($payload['chunk'])->toBe(5);
});

it('serializes only the name and the caption to the lazy payload', function () {
    $items = [['id' => 1, 'name' => 'general', 'caption' => '1 server', 'region' => 'sa-east-1']];

    $payload = list_lazy_payload(Blade::render('<x-list :items="$items" height="60" lazy />', compact('items')));

    expect($payload['items'][0])->toBe(['name' => 'general', 'caption' => '1 server']);
});

it('keeps a missing caption as null in the lazy payload', function () {
    $items = [['name' => 'general']];

    $payload = list_lazy_payload(Blade::render('<x-list :items="$items" height="60" lazy />', compact('items')));

    expect($payload['items'][0]['caption'])->toBeNull();
});

it('renders the lazy rows through a template instead of one component per item', function () {
    $items = [
        ['name' => 'general', 'caption' => '1 server'],
        ['name' => 'production', 'caption' => '2 servers'],
    ];

    $html = Blade::render('<x-list :items="$items" height="60" lazy />', compact('items'));

    expect($html)
        ->toContain('<template x-for="(item, index) in visible"')
        ->toContain('x-text="item.name"')
        ->toContain('x-text="item.caption"')
        ->toContain('x-bind:data-list-name="item.name"')
        ->and(substr_count($html, 'data-list-row'))->toBe(1)
        ->and($html)->not->toContain('>general<');
});

it('renders the sentinel that reveals the next lazy slice', function () {
    $items = [['name' => 'general']];

    expect(Blade::render('<x-list :items="$items" height="60" lazy />', compact('items')))
        ->toContain('tallstackui_list_sentinel')
        ->toContain('x-intersect.margin.100px="more()"')
        ->toContain('x-ref="sentinel"')
        ->toContain('x-ref="scroll"');
});

it('keeps the lazy rows under the list.items customization', function () {
    $items = [['name' => 'general']];
    $customization = TallStackUi::customize('list', scope: 'items');

    expect(Blade::render('<x-list :items="$items" height="60" lazy />', compact('items')))
        ->toContain('text-sm font-medium text-secondary-700')
        ->toContain('text-xs text-secondary-500')
        ->and($customization)->not->toBeNull();
});

it('renders the label, the hint, the search and the empty state in lazy mode', function () {
    $items = [['name' => 'general']];

    $component = '<x-list :items="$items" height="60" lazy searchable label="Tags" hint="Manage your tags." />';

    expect(Blade::render($component, compact('items')))
        ->toContain('Tags', 'Manage your tags.')
        ->toContain('tallstackui_list_search')
        ->toContain('tallstackui_list_empty');
});

it('does not enter the lazy mode when the attribute is disabled', function () {
    $items = [['name' => 'general']];

    expect(Blade::render('<x-list :items="$items" height="60" :lazy="false" />', compact('items')))
        ->toContain('tallstackui_list([])')
        ->toContain('>general<')
        ->not->toContain('tallstackui_list_sentinel');
});

it('cannot render the lazy mode without the items', function () {
    $this->expectException(ViewException::class);

    expect('<x-list height="60" lazy><x-list.items name="general" /></x-list>')->render();
});

it('cannot render the lazy mode without the height', function () {
    $items = [['name' => 'general']];

    $this->expectException(ViewException::class);

    Blade::render('<x-list :items="$items" lazy />', compact('items'));
});

it('cannot render the lazy mode with a slice below one', function () {
    $items = [['name' => 'general']];

    $this->expectException(ViewException::class);

    Blade::render('<x-list :items="$items" height="60" lazy="0" />', compact('items'));
});

it('cannot render the lazy mode with @interact', function (string $interaction) {
    $items = [['name' => 'general', 'id' => 1]];

    $component = <<<BLADE
    <x-list :items="\$items" height="60" lazy>
        @interact('{$interaction}', \$item)
            <span>Foo</span>
        @endinteract
    </x-list>
    BLADE;

    $this->expectException(ViewException::class);

    Blade::render($component, compact('items'));
})->with(['item_caption', 'item_action', 'item_menu']);

it('can render the skeleton instead of the content')
    ->expect('<x-list skeleton><x-list.items name="general" /></x-list>')
    ->render()
    ->toContain('animate-pulse')
    ->not->toContain('general');

it('can render the skeleton with the default item count', function () {
    $html = Blade::render('<x-list skeleton />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(12);
});

it('can render the skeleton with a custom item count', function () {
    $html = Blade::render('<x-list skeleton="6" />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(18);
});

it('can render the skeleton with the label, the search and the hint', function () {
    $html = Blade::render('<x-list skeleton="1" searchable label="Tags" hint="Foo" />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(6);
});

it('can render the skeleton honoring the height')
    ->expect('<x-list skeleton height="60" />')
    ->render()
    ->toContain('max-h-60');

it('cannot render the skeleton with a count below one', function () {
    $this->expectException(ViewException::class);

    expect('<x-list skeleton="0" />')->render();
});

describe('compact', function () {
    it('tightens the rows, the search and the empty state', function () {
        $component = <<<'HTML'
        <x-list compact searchable :items="[['name' => 'Foo', 'caption' => 'Bar']]" />
        HTML;

        expect($component)->render()
            ->toContain('px-3 py-1 [content-visibility:auto]')
            ->toContain('flex h-9 items-center')
            ->toContain('justify-center px-3 py-3')
            ->not->toContain('px-3 py-2 [content-visibility:auto]')
            ->not->toContain('flex h-11 items-center')
            ->not->toContain('justify-center px-3 py-6');
    });

    it('keeps the roomy rhythm by default', function () {
        $component = <<<'HTML'
        <x-list searchable :items="[['name' => 'Foo', 'caption' => 'Bar']]" />
        HTML;

        expect($component)->render()
            ->toContain('px-3 py-2 [content-visibility:auto]')
            ->toContain('flex h-11 items-center')
            ->toContain('justify-center px-3 py-6')
            ->not->toContain('px-3 py-1 [content-visibility:auto]')
            ->not->toContain('flex h-9 items-center');
    });

    it('reaches the items written in the slot', function () {
        $component = <<<'HTML'
        <x-list compact>
            <x-list.items name="Foo" />
        </x-list>
        HTML;

        expect($component)->render()->toContain('px-3 py-1 [content-visibility:auto]');
    });

    it('reaches the lazy rows', function () {
        $component = <<<'HTML'
        <x-list compact height="60" :lazy="2" :items="[['name' => 'Foo'], ['name' => 'Bar']]" />
        HTML;

        expect($component)->render()->toContain('px-3 py-1 [content-visibility:auto]');
    });

    it('tightens the skeleton too', function () {
        expect('<x-list skeleton searchable compact />')
            ->render()
            ->toContain('gap-x-2 px-3 py-1.5')
            ->toContain('flex h-9 items-center')
            ->not->toContain('gap-x-2 px-3 py-2.5');
    });
});
