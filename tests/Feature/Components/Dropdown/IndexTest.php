<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu">
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('Logout');
});

it('can render positions', function (string $position) {
    $dropdown = <<<HTML
    <x-dropdown text="Menu" position="$position">
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('x-anchor.'.$position)
        ->assertSee('Logout');
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

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('Logout')
        ->assertDontSee('x-data={ show : false, animate : false }', false);
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

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('Open')
        ->assertSee('<button', false)
        ->assertSee('Logout');
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

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('Foo bar')
        ->assertSee('Logout');
});

it('cannot render unaceptable positions', function (string $position) {
    $this->expectException(ViewException::class);

    $dropdown = <<<HTML
    <x-dropdown text="Menu" position="$position">
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('x-anchor.'.$position)
        ->assertSee('Logout');
})->with(['foo', 'bar', 'baz']);
