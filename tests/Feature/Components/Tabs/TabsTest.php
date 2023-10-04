<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-tabs :options="['Foo', 'Bar']" selected="Foo">
        <x-tabs.item tab="A">
            Foo
        </x-tabs.item>
        <x-tabs.item tab="B">
            Bar
        </x-tabs.item>
    </x-tabs>
HTML;

    $this->blade($component)
        ->assertSee('Foo')
        ->assertSee('Bar');
});

it('can render rounded', function () {
    $component = <<<'HTML'
    <x-tabs :options="['Foo', 'Bar']" selected="Foo">
        <x-tabs.item tab="A">
            Foo
        </x-tabs.item>
        <x-tabs.item tab="B">
            Bar
        </x-tabs.item>
    </x-tabs>
HTML;

    $this->blade($component)
        ->assertSee('rounded-t-lg')
        ->assertSee('rounded-bl-lg rounded-br-lg rounded-tr-lg');
});

it('can render squared', function () {
    config()->set('tasteui.personalizations.tabs.square', true);

    $component = <<<'HTML'
    <x-tabs :options="['Foo', 'Bar']" selected="Foo">
        <x-tabs.item tab="A">
            Foo
        </x-tabs.item>
        <x-tabs.item tab="B">
            Bar
        </x-tabs.item>
    </x-tabs>
HTML;

    $this->blade($component)
        ->assertDontSee('rounded-bl-lg rounded-br-lg rounded-tr-lg');
});
