<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Tag\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-tag />')
    ->render()
    ->toContain('<input');

it('can render with prefix')
    ->expect('<x-tag prefix="@" />')
    ->render()
    ->toContain('<input');

it('can render with label and hint')
    ->expect('<x-tag label="Foo bar" hint="Bar baz" />')
    ->render()
    ->toContain('<input')
    ->toContain('Foo bar')
    ->toContain('Bar baz');

it('can render with tags', function () {
    $component = <<<'HTML'
    <x-tag :value="['foo', 'bar', 'baz']" />
    HTML;

    expect($component)->render()
        ->toContain('foo', 'bar', 'baz');
});

it('cannot render with prefix with two strings', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-tag prefix="@@" />
    HTML;

    expect($component)->render();
});

it('can render with lazy')
    ->expect('<x-tag lazy="3" />')
    ->render()
    ->toContain('tallstackui_formTag')
    ->toContain('3');

it('cannot render with lazy less than one', function () {
    $this->expectException(ViewException::class);

    $component = <<<'HTML'
    <x-tag lazy="0" />
    HTML;

    expect($component)->render();
});

describe('options', function () {
    it('can render the floating list of reusable tags')
        ->expect('<x-tag :options="[\'php\', \'laravel\']" />')
        ->render()
        ->toContain('tallstackui_tag_options')
        ->toContain('php')
        ->toContain('laravel');

    it('cannot render the floating list without options nor the after slot')
        ->expect('<x-tag />')
        ->render()
        ->not->toContain('tallstackui_tag_options');

    it('can render the after slot')
        ->expect('<x-tag :options="[\'php\']"><x-slot:after>Create</x-slot:after></x-tag>')
        ->render()
        ->toContain('tallstackui_tag_after')
        ->toContain('Create');

    it('can render the after slot as the only reason to open the list')
        ->expect('<x-tag><x-slot:after>Create</x-slot:after></x-tag>')
        ->render()
        ->toContain('tallstackui_tag_options')
        ->toContain('Create');

    it('can render the empty message')
        ->expect('<x-tag :options="[\'php\']" />')
        ->render()
        ->toContain('No results found');

    it('can override the empty message')
        ->expect('<x-tag :options="[\'php\']" :placeholders="[\'empty\' => \'Nothing here\']" />')
        ->render()
        ->toContain('Nothing here');

    it('casts the options to strings and drops the duplicates', function () {
        $component = new Component(options: [1, '1', 2]);

        expect($component->options)->toBe(['1', '2']);
    });

    it('accepts the options as a collection', function () {
        $component = new Component(options: collect(['php', 'laravel']));

        expect($component->options)->toBe(['php', 'laravel']);
    });
});
