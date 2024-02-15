<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-tab selected="Foo">
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
        <x-tab.items tab="B">
            Bar
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('Foo', 'Bar');
});

it('can render using left slot')->todo();

it('can render using right slot')->todo();
