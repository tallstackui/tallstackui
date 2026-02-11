<?php

uses(Tests\TestCase::class)->group('Feature');

it('can render with hint prop')
    ->expect('<x-hint hint="This is a hint" />')
    ->render()
    ->toContain('This is a hint')
    ->toContain('text-sm');

it('can render with slot content', function () {
    $component = <<<'HTML'
    <x-hint>Slot hint content</x-hint>
    HTML;

    expect($component)->render()
        ->toContain('Slot hint content')
        ->toContain('text-sm');
});
