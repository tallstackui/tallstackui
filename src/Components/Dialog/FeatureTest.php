<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-dialog />')
    ->render()
    ->toContain('role="dialog"');

it('uses the primary palette for the confirmation icon')
    ->expect('<x-dialog />')
    ->render()
    ->toContain('bg-primary-100')
    ->toContain('text-primary-600')
    ->toContain('dark:text-primary-500')
    ->not->toContain('bg-secondary-100')
    ->not->toContain('dark:text-dark-500');
