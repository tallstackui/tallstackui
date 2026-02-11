<?php

uses(Tests\TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-radio />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('form-radio');

it('can render with label')
    ->expect('<x-radio label="Option A" />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('Option A');

it('can render with left position')
    ->expect('<x-radio label="Option A" position="left" />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('Option A')
    ->toContain('mr-2');

it('can render with right position')
    ->expect('<x-radio label="Option A" position="right" />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('Option A')
    ->toContain('ml-2');

it('can render with size xs')
    ->expect('<x-radio xs />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-3')
    ->toContain('w-3');

it('can render with size sm')
    ->expect('<x-radio sm />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-4')
    ->toContain('w-4');

it('can render with default size md')
    ->expect('<x-radio />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-5')
    ->toContain('w-5');

it('can render with size lg')
    ->expect('<x-radio lg />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-6')
    ->toContain('w-6');
