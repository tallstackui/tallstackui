<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render with text')
    ->expect('<x-side-bar.separator text="Section" />')
    ->render()
    ->toContain('Section');

it('can render simple style', function () {
    $component = <<<'HTML'
    <x-side-bar.separator text="Section" simple />
    HTML;

    expect($component)->render()
        ->toContain('Section');
});

it('can render line style', function () {
    $component = <<<'HTML'
    <x-side-bar.separator text="Section" line />
    HTML;

    expect($component)->render()
        ->toContain('Section');
});
