<?php

use Illuminate\Support\Facades\Blade;

it('does not leak ancestor x-slot:left into select.styled rendered later (issue #1276)', function () {
    $component = <<<'HTML'
    <x-tab selected="Foo">
        <x-tab.items tab="Foo">
            <x-slot:left>
                <span>icon</span>
            </x-slot:left>
            <x-select.styled label="MaterialLabel" :options="['a', 'b']" />
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('MaterialLabel')
        ->not->toContain('null.replace');
});

it('does not leak ancestor x-slot:left into select.native rendered later (issue #1276)', function () {
    $component = <<<'HTML'
    <x-tab selected="Foo">
        <x-tab.items tab="Foo">
            <x-slot:left>
                <span>icon</span>
            </x-slot:left>
            <x-select.native label="NativeLabel" :options="['a', 'b']" />
        </x-tab.items>
    </x-tab>
    HTML;

    expect($component)->render()
        ->toContain('NativeLabel');
});

it('preserves side detection when select.native is nested inside an active x-slot:left (input.select usage)', function () {
    $component = <<<'HTML'
    <x-input.select label="Phone" icon="phone">
        <x-slot:left>
            <x-select.native :options="['+1', '+44']" />
        </x-slot:left>
    </x-input.select>
    HTML;

    $rendered = Blade::render($component, [], true);

    expect($rendered)->toContain('rounded-r-none');
});

it('preserves side detection when select.styled is nested inside an active x-slot:right (input.select usage)', function () {
    $component = <<<'HTML'
    <x-input.select label="Email" icon="envelope">
        <x-slot:right>
            <x-select.styled :options="['@gmail.com', '@yahoo.com']" />
        </x-slot:right>
    </x-input.select>
    HTML;

    $rendered = Blade::render($component, [], true);

    expect($rendered)->toContain('rounded-l-none');
});

it('does not render the side-mode marker when select has no surrounding slot', function () {
    $component = <<<'HTML'
    <x-select.styled label="NoSide" :options="['a', 'b']" />
    HTML;

    expect($component)->render()
        ->not->toContain('replace(\':count\', quantity)');
});
