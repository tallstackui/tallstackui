<?php

it('can render')
    ->expect('<x-icon icon="users" />')
    ->render()
    ->toContain('<svg');

it('can render solid')
    ->expect('<x-icon icon="users" solid />')
    ->render()
    ->toContain('<svg');

it('can render outline')
    ->expect('<x-icon icon="users" outline />')
    ->render()
    ->toContain('<svg');

it('can render with error')
    ->expect('<x-icon icon="users" outline error />')
    ->render()
    ->toContain('text-red-500');
