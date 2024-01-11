<?php

use TallStackUi\View\Components\Form\Password;

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
        ->toContain(__('tallstack-ui::messages.password.rules.title'))
        ->toContain(__('tallstack-ui::messages.password.rules.formats.min', ['min' => Password::defaults()['min']]))
        ->toContain(__('tallstack-ui::messages.password.rules.formats.symbols', ['symbols' => '!@#']))
        ->toContain(__('tallstack-ui::messages.password.rules.formats.numbers'))
        ->toContain(__('tallstack-ui::messages.password.rules.formats.mixed'));
});
