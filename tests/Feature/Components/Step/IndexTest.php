<?php

it('can render', function () {
    $component = <<<'HTML'
    <x-step selected="1">
        <x-step.items step="1" title="Foo">
            Foo
        </x-step.items>
        <x-step.items step="2" title="Bar">
            Bar
        </x-step.items>
    </x-step>
    HTML;

    expect($component)->render()
        ->toContain('Foo', 'Bar');
});
