<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

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

it('can render with custom icons through the icon prop', function () {
    $component = <<<'HTML'
    <x-clipboard text="FooBar" :icon="['copy' => 'cog', 'copied' => 'pencil']" />
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBar')
        ->toContain('svg')
        ->not->toContain('type="text"');
});

it('can render with only one of the icon states customized', function () {
    $component = <<<'HTML'
    <x-clipboard text="FooBar" :icon="['copy' => 'cog']" />
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBar')
        ->toContain('svg');
});

it('can render the input when the icon is false')
    ->expect('<x-clipboard text="FooBar" :icon="false" />')
    ->render()
    ->toContain('type="text"');

it('cannot use unknown keys in the icon array', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Clipboard: The [icon] array only accepts the keys [copy, copied]. Received: [coppied].');

    expect('<x-clipboard text="FooBar" :icon="[\'copy\' => \'cog\', \'coppied\' => \'pencil\']" />')->render();
});

it('cannot use label & hint with icon style', function () {
    $component = <<<'HTML'
    <x-clipboard label="Content" hint="Copy here" text="FooBar" icon />
    HTML;

    expect($component)
        ->render()
        ->toContain('FooBar')
        ->not->toContain('Content')
        ->not->toContain('Copy here');
});
