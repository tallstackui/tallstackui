<?php

it('can render')
    ->expect('<x-clipboard text="FooBar" />')
    ->render()
    ->toContain('FooBar');

it('can render in left')
    ->expect('<x-clipboard text="FooBar" left />')
    ->render()
    ->toContain('FooBar');

it('can render with label & hint')
    ->expect('<x-clipboard label="Text" hint="Your password" text="FooBar" />')
    ->render()
    ->toContain('Text')
    ->toContain('Your password')
    ->toContain('FooBar');

it('can render secret')
    ->expect('<x-clipboard text="FooBar" secret />')
    ->render()
    ->toContain('password')
    ->toContain('FooBar');

it('can render icon')
    ->expect('<x-clipboard text="FooBar" icon />')
    ->render()
    ->toContain('FooBar');

it('can render with custom icons', function () {
    $component = <<<'HTML'
    <x-clipboard text="FooBar" icon :icons="['copy' => 'cog', 'copied' => 'pencil']" />
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBar')
        ->toContain('svg');
});
