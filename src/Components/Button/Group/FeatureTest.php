<?php

use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render with role group')
    ->expect('<x-button.group><x-button text="A" /></x-button.group>')
    ->render()
    ->toContain('role="group"');

it('can render slotted children')
    ->expect('<x-button.group><x-button text="Years" /><x-button text="Months" /></x-button.group>')
    ->render()
    ->toContain('Years')
    ->toContain('Months');

it('can render horizontal layout by default')
    ->expect('<x-button.group><x-button text="A" /></x-button.group>')
    ->render()
    ->toContain('isolate')
    ->toContain('inline-flex')
    ->toContain(htmlspecialchars('[&>*:first-child]:rounded-l-md'))
    ->toContain(htmlspecialchars('[&>*:last-child]:rounded-r-md'))
    ->toContain(htmlspecialchars('[&>*:not(:first-child)]:-ml-px'))
    ->not->toContain('flex-col');

it('can render vertical layout')
    ->expect('<x-button.group vertical><x-button text="A" /></x-button.group>')
    ->render()
    ->toContain('flex-col')
    ->toContain(htmlspecialchars('[&>*:first-child]:rounded-t-md'))
    ->toContain(htmlspecialchars('[&>*:last-child]:rounded-b-md'))
    ->toContain(htmlspecialchars('[&>*:not(:first-child)]:-mt-px'))
    ->not->toContain('rounded-l-md');

it('can render aria-label when label is passed')
    ->expect('<x-button.group aria-label="Date range"><x-button text="A" /></x-button.group>')
    ->render()
    ->toContain('aria-label="Date range"');

it('cannot render aria-label when label is null')
    ->expect('<x-button.group><x-button text="A" /></x-button.group>')
    ->render()
    ->not->toContain('aria-label');

it('does not regress isolated buttons outside the group')
    ->expect('<x-button text="Lonely" />')
    ->render()
    ->toContain('rounded-md');

it('can merge user attributes on the wrapper')
    ->expect('<x-button.group class="ml-4" id="foo"><x-button text="A" /></x-button.group>')
    ->render()
    ->toContain('ml-4')
    ->toContain('id="foo"');

it('can render the shadow by default')
    ->expect('<x-button.group><x-button text="A" /></x-button.group>')
    ->render()
    ->toContain('shadow-xs');

it('can render shadowless')
    ->expect('<x-button.group shadowless><x-button text="A" subtle /></x-button.group>')
    ->render()
    ->not->toContain('shadow-xs');
