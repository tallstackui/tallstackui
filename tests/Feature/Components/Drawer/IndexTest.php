<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $component = <<<'HTML'
    <x-drawer title="Foo Bar" footer="Foo bar baz" position="left" size="lg">
    Bar Baz
    </x-drawer>
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
    <x-drawer wire="">
    Bar Baz
    </x-drawer>
    HTML;

    expect($component)->render()
        ->toContain('Foo bar')
        ->toContain('Bar Baz')
        ->toContain('Foo bar baz');
});

it('can thrown exception when size is unacceptable', function (string $size) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Drawer: The [size] must be one of the following: [sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full]');

    $component = <<<'HTML'
    <x-drawer size="{{ size }}">
        Bar Baz
    </x-drawer>
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

it('can thrown exception when position is unacceptable', function (string $position) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Drawer: The [position] must be one of the following: [bottom, left, right, top]');

    $component = <<<'HTML'
    <x-drawer position="{{ position }}">
    Bar Baz
    </x-drawer>
    HTML;

    $component = str_replace('{{ position }}', $position, $component);

    expect($component)->render()
        ->toContain('Bar Baz');
})->with([
    'foo',
    'bar',
    'top-right',
    'bottom-end',
]);

it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Drawer: The [z-index] must start with z- prefix');

    $component = <<<'HTML'
    <x-drawer z-index="50">
    Bar Baz
    </x-drawer>
    HTML;

    expect($component)->render()
        ->toContain('Bar Baz');
});
