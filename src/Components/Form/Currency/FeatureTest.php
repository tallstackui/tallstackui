<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-currency />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-currency label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-currency label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with prefix and suffix', function () {
    $component = <<<'HTML'
    <x-currency symbol currency />
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('$')
        ->toContain('USD');
});

it('can render with different prefix and suffix', function () {
    config()->set('app.locale', 'pt_BR');

    $component = <<<'HTML'
    <x-currency locale="pt-BR" symbol currency />
    HTML;

    expect($component)->render()
        ->toContain('<input')
        ->toContain('R$')
        ->toContain('BRL');
});

it('cannot use precision lower than decimals', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [precision] must be greater than or equal to [decimals].');

    expect('<x-currency :decimals="3" :precision="2" />')
        ->render();
});
