<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-tab :options="['Foo', 'Bar']" selected="Foo">
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
        <x-tab.items tab="B">
            Bar
        </x-tab.items>
    </x-tab>
HTML;

    $this->blade($component)
        ->assertSee('Foo')
        ->assertSee('Bar');
});

it('can render as entangled', function () {
    $component = <<<'HTML'
    <x-tab :options="['Foo', 'Bar']" wire:model="Foo">
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
        <x-tab.items tab="B">
            Bar
        </x-tab.items>
    </x-tab>
HTML;

    $this->blade($component)
        ->assertSee('Foo')
        ->assertSee('Bar');
});

it('can render as live entangled', function () {
    $component = <<<'HTML'
    <x-tab :options="['Foo', 'Bar']" wire:model.live="Foo">
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
        <x-tab.items tab="B">
            Bar
        </x-tab.items>
    </x-tab>
HTML;

    $this->blade($component)
        ->assertSee('Foo')
        ->assertSee('Bar');
});
