<?php

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
        ->toContain(trans('tallstack-ui::messages.password.rules.title'))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.min', ['min' => config('tallstackui.settings.form.password.rules')['min']]))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.symbols', ['symbols' => '!@#']))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.numbers'))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.mixed'));
});

it('can render with rules using default', function () {
    $component = <<<'HTML'
    <x-password generator />
    HTML;

    expect($component)
        ->render()
        ->toContain(trans('tallstack-ui::messages.password.rules.title'))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.min', ['min' => config('tallstackui.settings.form.password.rules')['min']]))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.symbols', ['symbols' => '!@#$%^&amp;*()_+-=']))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.numbers'))
        ->toContain(trans('tallstack-ui::messages.password.rules.formats.mixed'));
});
