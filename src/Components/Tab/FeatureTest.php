<?php

uses(Tests\TestCase::class)->group('Feature');

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

it('can render centered', function () {
    $component = <<<'HTML'
    <x-tab selected="A" centered>
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
        <x-tab.items tab="B">
            Bar
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('justify-center');
});

it('can render with title', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
        <x-tab.items tab="A" title="First Tab">
            First Tab Content
        </x-tab.items>
        <x-tab.items tab="B" title="Second Tab">
            Second Tab Content
        </x-tab.items>
    </x-tab>
    HTML;
    expect($component)->render()
        ->toContain('First Tab Content', 'Second Tab Content');
});

it('can render with when matching current url', function () {
    $url = request()->url();

    $component = '<x-tab selected="A"><x-tab.items tab="A" when="'.$url.'">Matched Content</x-tab.items><x-tab.items tab="B" when="https://not-matching.test/other">Hidden Content</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('Matched Content')
        ->not->toContain('Hidden Content');
});
