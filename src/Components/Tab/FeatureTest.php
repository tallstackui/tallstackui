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

    $component = '<x-tab selected="A"><x-tab.items tab="A" href="'.$url.'">Matched Content</x-tab.items><x-tab.items tab="B" href="https://not-matching.test/other">Hidden Content</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('Matched Content')
        ->not->toContain('Hidden Content');
});

it('does not render slot when url does not match', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
        <x-tab.items tab="A" href="https://not-matching.test/other">
            Should Not Render
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->not->toContain('Should Not Render');
});

it('renders slot when no when attribute is set', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
        <x-tab.items tab="A">
            Always Renders
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('Always Renders');
});

it('passes navigate to alpine data', function () {
    $url = request()->url();

    $component = '<x-tab selected="A"><x-tab.items tab="A" href="'.$url.'" navigate>Content</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('navigate: true');
});

it('passes navigateHover to alpine data', function () {
    $url = request()->url();

    $component = '<x-tab selected="A"><x-tab.items tab="A" href="'.$url.'" navigate-hover>Content</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('navigateHover: true');
});

it('auto selects tab when url matches', function () {
    $url = request()->url();

    $component = '<x-tab selected="B"><x-tab.items tab="A" href="'.$url.'">Content A</x-tab.items><x-tab.items tab="B">Content B</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain("selected = 'A'");
});
