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

it('can render reversed')
    ->expect('<x-avatar.group reverse><x-avatar text="AB" /><x-avatar text="CD" /></x-avatar.group>')
    ->render()
    ->toContain('flex-row-reverse')
    ->toContain('space-x-reverse');

it('does not apply reverse classes by default')
    ->expect('<x-avatar.group><x-avatar text="AB" /></x-avatar.group>')
    ->render()
    ->not->toContain('flex-row-reverse')
    ->not->toContain('space-x-reverse');
