<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $this->blade('<x-icon icon="users" />')
        ->assertSee('<svg', false);
});

it('can render solid', function () {
    $this->blade('<x-icon icon="users" solid />')
        ->assertSee('<svg', false);
});

it('can render outline', function () {
    $this->blade('<x-icon icon="users" outline />')
        ->assertSee('<svg', false);
});

it('can render with error', function () {
    $this->blade('<x-icon icon="users" outline error />')
        ->assertSee('text-red-500');
});

it('cannot render with inaceptable type', function (string $type) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The icon must be one of the following: [solid, outline]');

    $component = <<<'HTML'
        <x-icon icon="users" type="{{ type }}" />    
    HTML;

    $component = str_replace('{{ type }}', $type, $component);

    $this->blade($component)
        ->assertSee('<svg', false);
})->with([
    'foo-bar',
    'foo',
    'bar',
    'baz',
    'soli',
    'outlin',
]);
