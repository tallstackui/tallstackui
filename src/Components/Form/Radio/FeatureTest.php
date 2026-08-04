<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-radio />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('form-radio');

it('can render with label')
    ->expect('<x-radio label="Option A" />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('Option A');

it('can render with left position')
    ->expect('<x-radio label="Option A" position="left" />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('Option A')
    ->toContain('mr-2');

it('can render with right position')
    ->expect('<x-radio label="Option A" position="right" />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('Option A')
    ->toContain('ml-2');

it('can render with size xs')
    ->expect('<x-radio xs />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-3')
    ->toContain('w-3');

it('can render with size sm')
    ->expect('<x-radio sm />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-4')
    ->toContain('w-4');

it('can render with default size md')
    ->expect('<x-radio />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-5')
    ->toContain('w-5');

it('can render with size lg')
    ->expect('<x-radio lg />')
    ->render()
    ->toContain('type="radio"')
    ->toContain('h-6')
    ->toContain('w-6');

it('can render the label on the left through the slot', function () {
    $component = <<<'HTML'
    <x-radio id="plan">
        <x-slot:label left>Basic</x-slot:label>
    </x-radio>
    HTML;

    expect($component)->render()->toMatch('/Basic.*<input/s');
});

it('gives each option of a group its own id', function () {
    $component = <<<'HTML'
    <div>
        <x-radio name="plan" label="Basic" value="basic" />
        <x-radio name="plan" label="Pro" value="pro" />
        <x-radio name="plan" label="Team" value="team" />
    </div>
    HTML;

    expect($component)->render()
        ->toContain('id="plan-basic"')
        ->toContain('id="plan-pro"')
        ->toContain('id="plan-team"')
        ->toContain('for="plan-basic"')
        ->toContain('for="plan-pro"')
        ->toContain('for="plan-team"');
});

it('keeps an explicit id untouched', function () {
    expect('<x-radio name="plan" label="Basic" value="basic" id="custom" />')->render()
        ->toContain('id="custom"')
        ->toContain('for="custom"');
});

it('prints the validation error once for a group of options', function () {
    $this->withViewErrors(['plan' => 'The plan field is required.']);

    $component = <<<'HTML'
    <div>
        <x-radio name="plan" label="Basic" value="basic" />
        <x-radio name="plan" label="Pro" value="pro" />
        <x-radio name="plan" label="Team" value="team" />
    </div>
    HTML;

    expect(substr_count(Blade::render($component), '>The plan field is required.<'))->toBe(1);
});

it('still prints the error for a property nobody claimed yet', function () {
    $this->withViewErrors(['newsletter' => 'The newsletter field is required.']);

    expect('<x-checkbox name="newsletter" label="Subscribe" />')->render()
        ->toContain('The newsletter field is required.');
});
