<?php

uses(TestCase::class)->group('Feature');

use Illuminate\View\ViewException;
use Tests\TestCase;

it('can render')
    ->expect('<x-theme-switch />')
    ->render()
    ->toContain('setAs');

it('can render simple variation')
    ->expect('<x-theme-switch simple />')
    ->render()
    ->toContain('themeSwitch');

it('can render block variation')
    ->expect('<x-theme-switch block />')
    ->render()
    ->toContain('w-full')
    ->toContain('flex flex-1 items-center justify-center');

it('cannot use only-icons without simple', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [only-icons] property requires [simple] to be enabled.');

    expect('<x-theme-switch only-icons />')->render();
});

it('cannot use block with simple', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [block] property is not supported with [simple] variation.');

    expect('<x-theme-switch block simple />')->render();
});
