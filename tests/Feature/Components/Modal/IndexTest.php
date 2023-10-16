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
