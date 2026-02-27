<?php

it('can render with default bottom position', function () {
    expect('<x-command-palette request="/api/search" />')
        ->render()
        ->toContain('items-end', 'rounded-t-xl');
});

it('can render with centered position', function () {
    expect('<x-command-palette request="/api/search" :centered="true" />')
        ->render()
        ->toContain('items-center', 'p-4', 'rounded-xl')
        ->not->toContain('items-end', 'rounded-t-xl');
});
