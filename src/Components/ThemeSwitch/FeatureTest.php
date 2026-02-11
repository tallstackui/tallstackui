<?php

uses(Tests\TestCase::class)->group('Feature');

use Illuminate\View\ViewException;

it('can render')
    ->expect('<x-theme-switch />')
    ->render()
    ->toContain('setAs');

it('can render simple variation')
    ->expect('<x-theme-switch simple />')
    ->render()
    ->toContain('themeSwitch');

it('cannot use only-icons without simple', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [only-icons] property requires [simple] to be enabled.');

    expect('<x-theme-switch only-icons />')->render();
});
