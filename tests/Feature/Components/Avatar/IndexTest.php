<?php

it('can render')
    ->expect('<x-avatar label="Lorem" />')
    ->render()
    ->toContain('Lorem');

it('can render sm')
    ->expect('<x-avatar label="Lorem" sm />')
    ->render()
    ->toContain('w-8 h-8');

it('can render md')
    ->expect('<x-avatar label="Lorem" md />')
    ->render()
    ->toContain('w-12 h-12');

it('can render lg')
    ->expect('<x-avatar label="Lorem" lg />')
    ->render()
    ->toContain('w-14 h-14');

it('can render square')
    ->expect('<x-avatar label="Lorem" lg square />')
    ->render()
    ->not
    ->toContain('rounded-full');

it('can render placeholder')
    ->expect('<x-avatar />')
    ->render()
    ->toContain('svg');

it('can render image')
    ->expect('<x-avatar image="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png" />')
    ->render()
    ->toContain('src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"');

it('can render image with alt')
    ->expect('<x-avatar image="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png" text="Beca" />')
    ->render()
    ->toContain('src="https://cdn.dribbble.com/users/17793/screenshots/16101765/media/beca221aaebf1d3ea7684ce067bc16e5.png"')
    ->toContain('alt="Beca');
