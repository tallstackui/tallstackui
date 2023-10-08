<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-tabs :options="['Foo', 'Bar']" selected="Foo">
        <x-tabs.items tab="A">
            Foo
        </x-tabs.items>
        <x-tabs.items tab="B">
            Bar
        </x-tabs.items>
    </x-tabs>
HTML;

    $this->blade($component)
        ->assertSee('Foo')
        ->assertSee('Bar');
});

it('can render entangled', function () {
    $component = <<<'HTML'
    <x-tabs :options="['Foo', 'Bar']" wire:entangle="Foo">
        <x-tabs.items tab="A">
            Foo
        </x-tabs.items>
        <x-tabs.items tab="B">
            Bar
        </x-tabs.items>
    </x-tabs>
HTML;

    $this->blade($component)
        ->assertSee('Foo')
        ->assertSee('Bar');
});

it('can render rounded', function () {
    $component = <<<'HTML'
    <x-tabs :options="['Foo', 'Bar']" selected="Foo">
        <x-tabs.items tab="A">
            Foo
        </x-tabs.items>
        <x-tabs.items tab="B">
            Bar
        </x-tabs.items>
    </x-tabs>
HTML;

    $this->blade($component)
        ->assertSee('rounded-t-lg')
        ->assertSee('rounded-bl-lg rounded-br-lg rounded-tr-lg');
});

it('can render squared', function () {
    config()->set('tallstackui.personalizations.tabs.square', true);

    $component = <<<'HTML'
    <x-tabs :options="['Foo', 'Bar']" selected="Foo">
        <x-tabs.items tab="A">
            Foo
        </x-tabs.items>
        <x-tabs.items tab="B">
            Bar
        </x-tabs.items>
    </x-tabs>
HTML;

    $this->blade($component)
        ->assertDontSee('rounded-t-lg')
        ->assertDontSee('rounded-bl-lg rounded-br-lg rounded-tr-lg');
});
