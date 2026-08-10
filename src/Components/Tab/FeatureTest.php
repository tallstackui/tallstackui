<?php

use TallStackUi\Components\Tab\Main\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

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

it('can render with shadow and without border by default', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('shadow-md')
        ->not->toContain('shadow-none!')
        ->not->toContain('border border-gray-200');
});

it('can render shadowless', function () {
    $component = <<<'HTML'
    <x-tab selected="A" shadowless>
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('shadow-none!');
});

it('can render bordered', function () {
    $component = <<<'HTML'
    <x-tab selected="A" bordered>
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('border border-gray-200 dark:border-dark-600');
});

it('can render shadowless and bordered together', function () {
    $component = <<<'HTML'
    <x-tab selected="A" shadowless bordered>
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('shadow-none!')
        ->toContain('border border-gray-200 dark:border-dark-600');
});

it('can render the flat look through the global configuration', function () {
    $component = <<<'HTML'
    <x-tab selected="A">
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
    </x-tab>
    HTML;

    config()->set('ts-ui.components.tab.1.shadowless', true);
    config()->set('ts-ui.components.tab.1.bordered', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect($component)->render()
            ->toContain('shadow-none!')
            ->toContain('border border-gray-200');
    } finally {
        config()->set('ts-ui.components.tab.1.shadowless', false);
        config()->set('ts-ui.components.tab.1.bordered', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can let the flat look props win over the global configuration', function () {
    $component = <<<'HTML'
    <x-tab selected="A" :shadowless="false" :bordered="false">
        <x-tab.items tab="A">
            Foo
        </x-tab.items>
    </x-tab>
    HTML;

    config()->set('ts-ui.components.tab.1.shadowless', true);
    config()->set('ts-ui.components.tab.1.bordered', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect($component)->render()
            ->not->toContain('shadow-none!')
            ->not->toContain('border border-gray-200');
    } finally {
        config()->set('ts-ui.components.tab.1.shadowless', false);
        config()->set('ts-ui.components.tab.1.bordered', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
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

it('can render without content padding', function () {
    $component = '<x-tab paddingless><x-tab.items tab="A">Content A</x-tab.items></x-tab>';

    expect($component)->render()
        ->toContain('p-0!');
});

it('can render with content padding by default', function () {
    $component = '<x-tab><x-tab.items tab="A">Content A</x-tab.items></x-tab>';

    expect($component)->render()
        ->not->toContain('p-0!');
});
