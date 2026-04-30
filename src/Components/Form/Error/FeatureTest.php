<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render without errors', function () {
    View::share('errors', new ViewErrorBag);

    expect('<x-error property="name" />')
        ->render()
        ->not->toContain('text-red-500');
});

it('can render with validation error', function () {
    $bag = new MessageBag(['name' => 'The name field is required.']);
    $errors = new ViewErrorBag;
    $errors->put('default', $bag);

    View::share('errors', $errors);

    expect('<x-error property="name" />')
        ->render()
        ->toContain('text-red-500')
        ->toContain('The name field is required.');
});

it('exposes a reactive alpine binding when an error is present', function () {
    $bag = new MessageBag(['email' => 'Invalid email.']);
    $errors = new ViewErrorBag;
    $errors->put('default', $bag);

    View::share('errors', $errors);

    expect('<x-error property="email" />')
        ->render()
        ->toContain('$wire?.$errors?.has')
        ->toContain('$wire?.$errors?.first')
        ->toContain("'email'");
});

it('embeds the server-side message as the alpine fallback for non-livewire usage', function () {
    $bag = new MessageBag(['email' => 'Invalid email.']);
    $errors = new ViewErrorBag;
    $errors->put('default', $bag);

    View::share('errors', $errors);

    expect('<x-error property="email" />')
        ->render()
        ->toContain('Invalid email.');
});
