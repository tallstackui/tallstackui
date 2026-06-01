<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Currency\Component;
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
    app()->setLocale('pt_BR');

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

it('cannot use mutate and decimal together', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [mutate] and [decimal] cannot be used together.');

    expect('<x-currency mutate decimal />')->render();
});

it('inherits global mutate default from config', function () {
    config()->set('ts-ui.components.currency.1.mutate', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-currency />')
        ->render()
        ->toMatch('/tallstackui_formCurrency\(\s*null,\s*2,\s*4,\s*null,\s*true,\s*false,/');
});

it('inherits global decimal default from config', function () {
    config()->set('ts-ui.components.currency.1.decimal', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-currency />')
        ->render()
        ->toMatch('/tallstackui_formCurrency\(\s*null,\s*2,\s*4,\s*null,\s*false,\s*true,/');
});

it('per-instance prop overrides the global default', function () {
    config()->set('ts-ui.components.currency.1.mutate', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-currency :mutate="false" />')
        ->render()
        ->toMatch('/tallstackui_formCurrency\(\s*null,\s*2,\s*4,\s*null,\s*false,\s*false,/');
});
