<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $modal = <<<'HTML'
    <x-modal title="Foo Bar" footer="Foo bar baz">
    Bar Baz
    </x-modal>
    HTML;

    $this->blade($modal)
        ->assertSee('Foo bar')
        ->assertSee('Bar Baz')
        ->assertSee('Foo bar baz');
});

it('can thrown exception when wire is empty', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [wire] property cannot be an empty string');

    $modal = <<<'HTML'
    <x-modal wire="">
    Bar Baz
    </x-modal>
    HTML;

    $this->blade($modal)
        ->assertSee('Foo bar')
        ->assertSee('Bar Baz')
        ->assertSee('Foo bar baz');
});

it('can thrown exception when size is unnaceptable', function (string $size) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The modal size must be one of the following: [sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl]');

    $modal = <<<'HTML'
    <x-modal size="{{ size }}">
    Bar Baz
    </x-modal>
    HTML;

    $this->blade(str_replace('{{ size }}', $size, $modal))->assertSee('Bar Baz');
})->with([
    'foo',
    'bar',
    '8xl',
    '9xl',
    '10xl',
]);

it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The modal z-index must start with z- prefix');

    $modal = <<<'HTML'
    <x-modal z-index="50">
    Bar Baz
    </x-modal>
    HTML;

    $this->blade($modal)->assertSee('Bar Baz');
});
