<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-avatar.group><x-avatar text="AB" /><x-avatar text="CD" /></x-avatar.group>')
    ->render()
    ->toContain('AB')
    ->toContain('CD');

it('can render with overlap classes')
    ->expect('<x-avatar.group><x-avatar text="AB" /></x-avatar.group>')
    ->render()
    ->toContain('-space-x-2');
