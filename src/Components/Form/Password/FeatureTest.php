<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-password />')
    ->render()
    ->toContain('<input');

it('can render with label')
    ->expect('<x-password label="Foo bar" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar');

it('can render with label and hint')
    ->expect('<x-password label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Bar baz')
    ->toContain('Foo bar');

it('can render with rules', function () {
    $component = <<<'HTML'
    <x-password :rules="['min:8', 'symbols:!@#', 'numbers', 'mixed']" />
    HTML;

    expect($component)
        ->render()
        ->toContain(trans('ts-ui::messages.password.rules.title'))
        ->toContain(trans('ts-ui::messages.password.rules.formats.symbols', ['symbols' => '!@#']))
        ->toContain(trans('ts-ui::messages.password.rules.formats.numbers'))
        ->toContain(trans('ts-ui::messages.password.rules.formats.mixed'));
});

it('can render with rules using default', function () {
    $component = <<<'HTML'
    <x-password generator />
    HTML;

    expect($component)
        ->render()
        ->toContain(trans('ts-ui::messages.password.rules.title'))
        ->toContain(trans('ts-ui::messages.password.rules.formats.symbols', ['symbols' => '!@#$%^&amp;*()_+-=']))
        ->toContain(trans('ts-ui::messages.password.rules.formats.numbers'))
        ->toContain(trans('ts-ui::messages.password.rules.formats.mixed'));
});

it('can render the generator without a target')
    ->expect('<x-password generator />')
    ->render()
    ->toContain('null, null, null)');

it('can render the generator with a target')
    ->expect('<x-password generator="password_confirmation" />')
    ->render()
    ->toContain("'password_confirmation')");

it('cannot render the generator with an empty target', function () {
    $this->expectException(ViewException::class);

    expect('<x-password generator="" />')->render();
});

it('does not render the generator button while locked, but keeps the reveal', function (string $lock) {
    expect("<x-password name=\"foo\" generator {$lock} />")->render()
        ->not->toContain('tallstackui_form_password_generate')
        ->toContain('tallstackui_form_password_reveal');
})->with(['readonly', 'disabled']);

it('binds the input type with the explicit x-bind syntax')
    ->expect('<x-password />')
    ->render()
    ->toContain('x-bind:type="!show ? \'password\' : \'text\'"')
    ->not->toContain('::type');
