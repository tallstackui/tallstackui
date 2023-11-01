<?php

it('can render', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu">
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('right-0 origin-top-right')
        ->assertSee('Logout');
});

it('can render right side', function () {
    $dropdown = <<<'HTML'
    <x-dropdown text="Menu" right>
        <x-dropdown.items text="Settings" />
        <x-dropdown.items text="Logout" separator />
    </x-dropdown>
    HTML;

    $this->blade($dropdown)
        ->assertSee('Settings')
        ->assertSee('left-0 origin-top-left')
        ->assertSee('Logout');
});

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
        ->assertSee('right-0 origin-top-right')
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
        ->assertSee('right-0 origin-top-right')
        ->assertSee('Foo bar')
        ->assertSee('Logout');
});
