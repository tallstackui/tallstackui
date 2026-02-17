<?php

use TallStackUi\Support\CommandPalette\Callback;

it('creates redirect callback', function () {
    $callback = Callback::redirect('/users/1');

    expect($callback->toArray())
        ->toBe([
            'type' => 'redirect',
            'data' => ['to' => '/users/1'],
            'external' => false,
            'navigate' => false,
        ]);
});

it('creates external redirect callback', function () {
    $callback = Callback::redirect('https://google.com')->external();

    expect($callback->toArray())
        ->toBe([
            'type' => 'redirect',
            'data' => ['to' => 'https://google.com'],
            'external' => true,
            'navigate' => false,
        ]);
});

it('creates navigate redirect callback', function () {
    $callback = Callback::redirect('/dashboard')->navigate();

    expect($callback->toArray())
        ->toBe([
            'type' => 'redirect',
            'data' => ['to' => '/dashboard'],
            'external' => false,
            'navigate' => true,
        ]);
});

it('creates event callback', function () {
    $callback = Callback::event('user-selected');

    expect($callback->toArray())
        ->toBe([
            'type' => 'event',
            'data' => ['name' => 'user-selected'],
            'external' => false,
            'navigate' => false,
        ]);
});

it('creates event callback with params', function () {
    $callback = Callback::event('user-selected')->with(['id' => 1, 'role' => 'admin']);

    expect($callback->toArray())
        ->toBe([
            'type' => 'event',
            'data' => ['name' => 'user-selected', 'params' => ['id' => 1, 'role' => 'admin']],
            'external' => false,
            'navigate' => false,
        ]);
});

it('implements Arrayable interface', function () {
    $callback = Callback::redirect('/test');

    expect($callback)->toBeInstanceOf(\Illuminate\Contracts\Support\Arrayable::class);
});
