<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Accordion\Main\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render with slot items', function () {
    $component = <<<'HTML'
    <x-accordion>
        <x-accordion.items title="First" id="one">
            First content
        </x-accordion.items>
        <x-accordion.items title="Second" id="two">
            Second content
        </x-accordion.items>
    </x-accordion>
    HTML;

    expect($component)->render()
        ->toContain('First')
        ->toContain('Second')
        ->toContain('First content')
        ->toContain('Second content');
});

it('renders item with open prop as initially expanded in markup', function () {
    $component = <<<'HTML'
    <x-accordion>
        <x-accordion.items title="Opened" id="expanded-one" open>
            Visible body
        </x-accordion.items>
    </x-accordion>
    HTML;

    expect($component)->render()
        ->toContain('open: true')
        ->toContain('Visible body');
});

it('renders custom trigger slot over title', function () {
    $component = <<<'HTML'
    <x-accordion>
        <x-accordion.items title="IgnoredTitle" id="custom-trigger">
            <x-slot:trigger>
                <span class="custom-label">Hello Custom Trigger</span>
            </x-slot:trigger>
            Body
        </x-accordion.items>
    </x-accordion>
    HTML;

    expect($component)->render()
        ->toContain('Hello Custom Trigger')
        ->toContain('custom-label')
        ->not->toContain('IgnoredTitle');
});

it('renders custom icon slot replacing default chevron', function () {
    $component = <<<'HTML'
    <x-accordion>
        <x-accordion.items title="Has custom icon" id="custom-icon">
            <x-slot:icon>
                <svg class="custom-svg" viewBox="0 0 10 10"></svg>
            </x-slot:icon>
            Body
        </x-accordion.items>
    </x-accordion>
    HTML;

    expect($component)->render()
        ->toContain('custom-svg')
        ->not->toContain('chevron-down');
});

it('removes bordered classes when flat prop is set', function () {
    $bordered = '<x-accordion><x-accordion.items title="A" id="a">Body</x-accordion.items></x-accordion>';
    $flat = '<x-accordion flat><x-accordion.items title="A" id="a">Body</x-accordion.items></x-accordion>';

    expect($bordered)->render()->toContain('border');
    expect($flat)->render()->not->toContain('rounded-lg border border-gray-200');
});

it('can render with shadow by default')
    ->expect('<x-accordion><x-accordion.items title="A" id="a">Body</x-accordion.items></x-accordion>')
    ->render()
    ->toContain('shadow-md')
    ->not->toContain('shadow-none!');

it('can render shadowless')
    ->expect('<x-accordion shadowless><x-accordion.items title="A" id="a">Body</x-accordion.items></x-accordion>')
    ->render()
    ->toContain('shadow-none!');

it('can render shadowless through the global configuration', function () {
    config()->set('ts-ui.components.accordion.1.shadowless', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-accordion><x-accordion.items title="A" id="a">Body</x-accordion.items></x-accordion>')
        ->render()
        ->toContain('shadow-none!');

    config()->set('ts-ui.components.accordion.1.shadowless', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can let the shadowless prop win over the global configuration', function () {
    config()->set('ts-ui.components.accordion.1.shadowless', true);

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-accordion :shadowless="false"><x-accordion.items title="A" id="a">Body</x-accordion.items></x-accordion>')
        ->render()
        ->not->toContain('shadow-none!');

    config()->set('ts-ui.components.accordion.1.shadowless', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('throws when chevron prop is not left or right', function () {
    $this->expectException(ViewException::class);

    expect('<x-accordion chevron="up"><x-accordion.items title="A" id="a">B</x-accordion.items></x-accordion>')->render();
});

it('applies chevron-left cascade class on wrapper', function () {
    expect('<x-accordion chevron="left"><x-accordion.items title="A" id="a">B</x-accordion.items></x-accordion>')
        ->render()
        ->toContain('flex-row-reverse');
});
