<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-checkbox />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('form-checkbox');

it('can render with label')
    ->expect('<x-checkbox label="Accept terms" />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('Accept terms');

it('can render with left position')
    ->expect('<x-checkbox label="Accept terms" position="left" />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('Accept terms')
    ->toContain('mr-2');

it('can render with right position')
    ->expect('<x-checkbox label="Accept terms" position="right" />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('Accept terms')
    ->toContain('ml-2');

it('can render with size xs')
    ->expect('<x-checkbox xs />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-3')
    ->toContain('w-3');

it('can render with size sm')
    ->expect('<x-checkbox sm />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-4')
    ->toContain('w-4');

it('can render with default size md')
    ->expect('<x-checkbox />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-5')
    ->toContain('w-5');

it('can render with size lg')
    ->expect('<x-checkbox lg />')
    ->render()
    ->toContain('type="checkbox"')
    ->toContain('h-6')
    ->toContain('w-6');

it('can render the label on the left through the slot', function () {
    $component = <<<'HTML'
    <x-checkbox id="agree">
        <x-slot:label left>I agree</x-slot:label>
    </x-checkbox>
    HTML;

    // The left label is emitted before the input, the right one after it.
    expect($component)->render()
        ->toMatch('/I agree.*<input/s');
});
