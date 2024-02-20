<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
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
