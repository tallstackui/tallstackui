<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
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

it('does not match when there is no current route', function () {
    // Error views render with no route resolved at all, which is the worst
    // possible moment to throw: the page is already an error page.
    $component = <<<'HTML'
    <x-side-bar smart>
        <x-side-bar.item text="Reports" route="https://example.com/reports" />
    </x-side-bar>
    HTML;

    expect($component)->render()->toContain('Reports');
});

it('does not match when the current route has no name', function () {
    // A route declared without ->name() has no name to feed back into route().
    Route::get('/reports', fn (): string => Blade::render(<<<'HTML'
    <x-side-bar smart>
        <x-side-bar.item text="Reports" route="https://example.com/reports" />
    </x-side-bar>
    HTML));

    $this->get('/reports')
        ->assertOk()
        ->assertSee('Reports');
});
