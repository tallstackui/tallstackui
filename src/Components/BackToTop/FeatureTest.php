<?php

use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('tallstackui_backToTop');

it('can render with bottom-left position')
    ->expect('<x-back-to-top position="bottom-left" />')
    ->render()
    ->toContain('fixed')
    ->toContain('left-6');

it('can render with bottom-right position')
    ->expect('<x-back-to-top position="bottom-right" />')
    ->render()
    ->toContain('fixed')
    ->toContain('right-6');

it('cannot render with invalid position', function () {
    $this->expectException(ViewException::class);

    expect('<x-back-to-top position="top-right" />')->render();
});

it('can render with xs size')
    ->expect('<x-back-to-top xs />')
    ->render()
    ->toContain('h-8 w-8');

it('can render with sm size')
    ->expect('<x-back-to-top sm />')
    ->render()
    ->toContain('h-10 w-10');

it('can render with md size')
    ->expect('<x-back-to-top md />')
    ->render()
    ->toContain('h-12 w-12');

it('can render with lg size')
    ->expect('<x-back-to-top lg />')
    ->render()
    ->toContain('h-14 w-14');

it('can render with default md size')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('h-12 w-12');

it('can render with square shape')
    ->expect('<x-back-to-top square />')
    ->render()
    ->toContain('rounded-lg')
    ->not->toContain('rounded-full');

it('can render with round shape by default')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('rounded-full');

it('can render with custom icon')
    ->expect('<x-back-to-top icon="arrow-up" />')
    ->render()
    ->toContain('<svg');

it('can render with anchor')
    ->expect('<x-back-to-top anchor="#hero" />')
    ->render()
    ->toContain('#hero');

it('can render with immediate scroll')
    ->expect('<x-back-to-top immediate />')
    ->render()
    ->toContain('false');

it('can render with smooth scroll by default')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('true');

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-back-to-top color="$colors" />
    HTML;

    expect($component)
        ->render()
        ->toContain('tallstackui_backToTop');
})->with(colorsDataset());
