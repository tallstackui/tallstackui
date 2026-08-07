<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu">
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('Settings')
        ->toContain('Logout');
});

it('can render positions', function (string $position) {
    $dropdown = <<<HTML
    <x-dropdown text="Menu" position="$position">
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('Settings')
        ->toContain('x-anchor.'.$position)
        ->toContain('Logout');
})->with([
    'bottom', 'bottom-start', 'bottom-end', 'top', 'top-start', 'top-end', 'left', 'left-start', 'left-end', 'right', 'right-start', 'right-end',
]);

it('can render static', function () {
    $dropdown = <<<'HTML'
    <x-dropdown icon="cog" static>
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('Settings')
        ->toContain('Logout')
        ->not->toContain('x-data={ show : false, animate : false }');
});

it('can render action slot', function () {
    $dropdown = <<<'HTML'
    <x-dropdown>
        <x-slot:action>
            <x-button x-on:click="show = !show" sm outline>Open</x-button>
        </x-slot:action>
        <x-dropdown.items icon="cog" text="Settings" />
        <x-dropdown.items icon="arrow-left-on-rectangle" text="Logout" separator />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('Settings')
        ->toContain('Open')
        ->toContain('<button', false)
        ->toContain('Logout');
});

it('can render header slot', function () {
    $dropdown = <<<'HTML'
    <x-dropdown>
        <x-slot:header>
            Foo bar
        </x-slot:header>
        <x-dropdown.items icon="cog" text="Settings" />
        <x-dropdown.items icon="arrow-left-on-rectangle" text="Logout" separator />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('Settings')
        ->toContain('Foo bar')
        ->toContain('Logout');
});

it('renders default size and width as md', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu">
        <x-dropdown.items text="Settings" />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('data-tsui-dropdown-size="md"')
        ->toContain('data-tsui-dropdown-width="md"');
});

it('renders the chosen size and matches width by default', function (string $size) {
    $dropdown = <<<HTML
    <x-dropdown text="Menu" $size>
        <x-dropdown.items text="Settings" />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain("data-tsui-dropdown-size=\"$size\"")
        ->toContain("data-tsui-dropdown-width=\"$size\"");
})->with(['xs', 'sm', 'md', 'lg']);

it('respects width override regardless of size', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu" xs width="2xl">
        <x-dropdown.items text="Settings" />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('data-tsui-dropdown-size="xs"')
        ->toContain('data-tsui-dropdown-width="2xl"');
});

it('cannot accept invalid width', function () {
    $this->expectException(ViewException::class);

    $dropdown = <<<'HTML'
    <x-dropdown text="Menu" width="huge">
        <x-dropdown.items text="Settings" />
    </x-dropdown>
    HTML;

    expect($dropdown)->render();
});

it('submenu inherits size and width via DOM ancestor lookup', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu" lg width="2xl">
        <x-dropdown.submenu text="More">
            <x-dropdown.items text="Item" />
        </x-dropdown.submenu>
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('data-tsui-dropdown-size="lg"')
        ->toContain('data-tsui-dropdown-width="2xl"')
        ->toContain("\$el.closest('[data-tsui-dropdown-size]')")
        ->toContain('x-bind:data-tsui-dropdown-size="size"')
        ->toContain('x-bind:data-tsui-dropdown-width="width"');
});

it('cannot render unaceptable positions', function (string $position) {
    $this->expectException(ViewException::class);

    $dropdown = <<<HTML
    <x-dropdown text="Menu" position="$position">
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('Settings')
        ->toContain('x-anchor.'.$position)
        ->toContain('Logout');
})->with(['foo', 'bar', 'baz']);

it('can render with hover', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu" hover>
        <x-dropdown.items text="Settings" />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()
        ->toContain('pointerenter')
        ->toContain('pointerleave');
});

it('cannot render hover handlers by default', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu">
        <x-dropdown.items text="Settings" />
    </x-dropdown>
    HTML;

    expect($dropdown)->render()->not->toContain('pointerenter');
});
