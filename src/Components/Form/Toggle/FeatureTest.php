<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-toggle />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('appearance-none')
    ->toContain('rounded-full');

it('can render with label')
    ->expect('<x-toggle label="Enable notifications" />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('Enable notifications');

it('can render with left position')
    ->expect('<x-toggle label="Dark mode" position="left" />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('Dark mode')
    ->toContain('mr-2');

it('can render with right position')
    ->expect('<x-toggle label="Dark mode" position="right" />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('Dark mode')
    ->toContain('ml-2');

it('can render with size xs')
    ->expect('<x-toggle xs />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-2')
    ->toContain('w-2');

it('can render with size sm')
    ->expect('<x-toggle sm />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-3')
    ->toContain('w-3');

it('can render with default size md')
    ->expect('<x-toggle />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-4')
    ->toContain('w-4');

it('can render with size lg')
    ->expect('<x-toggle lg />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-5')
    ->toContain('w-5');
