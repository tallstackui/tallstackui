<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render', function () {
    $component = <<<'HTML'
    <x-modal title="Foo Bar" footer="Foo bar baz">
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Bar Baz')
        ->toContain('Foo bar baz');
});

it('can thrown exception when wire is empty', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [wire] property cannot be an empty string');

    $component = <<<'HTML'
    <x-modal wire="">
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Bar Baz')
        ->toContain('Foo bar baz');
});

it('can thrown exception when size is unnaceptable', function (string $size) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Modal: The [size] must be one of the following: [sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full]');

    $component = <<<'HTML'
    <x-modal size="{{ size }}">
    Bar Baz
    </x-modal>
    HTML;

    $component = str_replace('{{ size }}', $size, $component);

    expect($component)->render()
        ->toContain('Bar Baz');
})->with([
    'foo',
    'bar',
    '8xl',
    '9xl',
    '10xl',
]);

it('can render centered modal with items-center on all viewports', function () {
    $component = <<<'HTML'
    <x-modal title="Centered" center>
    Content
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('items-center')
        ->toContain('p-4')
        ->toContain('rounded-xl')
        ->not->toContain('items-end');
});

it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Modal: The [z-index] must start with z- prefix');

    $component = <<<'HTML'
    <x-modal z-index="50">
    Bar Baz
    </x-modal>
    HTML;

    expect($component)->render()
        ->toContain('Bar Baz');
});
