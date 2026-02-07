<?php

uses(Tests\TestCase::class);

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

it('can render with id on step items', function () {
    $component = <<<'HTML'
    <x-step selected="1">
        <x-step.items step="1" title="Foo" id="step-one">
            Foo
        </x-step.items>
        <x-step.items step="2" title="Bar" id="step-two">
            Bar
        </x-step.items>
    </x-step>
    HTML;

    expect($component)->render()
        ->toContain("id: 'step-one'")
        ->toContain("id: 'step-two'")
        ->toContain('x-bind:id="item.id"');
});

it('can render without id on step items', function () {
    $component = <<<'HTML'
    <x-step selected="1">
        <x-step.items step="1" title="Foo">
            Foo
        </x-step.items>
    </x-step>
    HTML;

    expect($component)->render()
        ->toContain('id: null');
});
