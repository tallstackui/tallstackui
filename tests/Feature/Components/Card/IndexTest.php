<?php

it('can render')
    ->expect('<x-card>Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar');

it('can render with title')
    ->expect('<x-card title="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Bar Baz');

it('can render with footer')
    ->expect('<x-card footer="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Bar Baz');

it('can render with title and footer')
    ->expect('<x-card title="Lorem Ipsum" footer="Bar Baz">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('Lorem Ipsum')
    ->toContain('Bar Baz');

it('can render with image')
    ->expect('<x-card image="https://via.placeholder.com/150">Foo bar</x-card>')
    ->render()
    ->toContain('Foo bar')
    ->toContain('https://via.placeholder.com/150');