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

it('can render entangled', function () {
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

it('can render rounded', function () {
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
        ->assertSee('rounded-t-lg')
        ->assertSee('rounded-bl-lg rounded-br-lg rounded-tr-lg');
});

it('can render squared', function () {
    config()->set('tallstackui.personalizations.tab.square', true);

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
        ->assertDontSee('rounded-t-lg')
        ->assertDontSee('rounded-bl-lg rounded-br-lg rounded-tr-lg');
});