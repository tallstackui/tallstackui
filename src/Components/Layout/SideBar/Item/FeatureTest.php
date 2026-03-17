<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render with text')
    ->expect('<x-side-bar.item text="Settings" />')
    ->render()
    ->toContain('Settings');

it('can render with href attribute')
    ->expect('<x-side-bar.item text="Dashboard" href="https://example.com" />')
    ->render()
    ->toContain('Dashboard')
    ->toContain('https://example.com');
