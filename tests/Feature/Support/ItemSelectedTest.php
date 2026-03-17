<?php

use Illuminate\Contracts\Support\Arrayable;
use TallStackUi\Support\CommandPalette\ItemSelected;

it('creates with required fields', function () {
    $item = new ItemSelected(search: 'test', label: 'John Doe', value: 1);

    expect($item->search)->toBe('test')
        ->and($item->label)->toBe('John Doe')
        ->and($item->value)->toBe(1)
        ->and($item->description)->toBeNull()
        ->and($item->image)->toBeNull()
        ->and($item->icon)->toBeNull()
        ->and($item->additional)->toBe([]);
});

it('creates with all fields', function () {
    $item = new ItemSelected(
        search: 'john',
        label: 'John Doe',
        value: 1,
        description: 'Developer',
        image: 'https://example.com/avatar.jpg',
        icon: '<svg>...</svg>',
        additional: ['role' => 'admin', 'department' => 'engineering'],
    );

    expect($item->search)->toBe('john')
        ->and($item->label)->toBe('John Doe')
        ->and($item->value)->toBe(1)
        ->and($item->description)->toBe('Developer')
        ->and($item->image)->toBe('https://example.com/avatar.jpg')
        ->and($item->icon)->toBe('<svg>...</svg>')
        ->and($item->additional)->toBe(['role' => 'admin', 'department' => 'engineering']);
});

it('implements Arrayable interface', function () {
    $item = new ItemSelected(search: 'test', label: 'Test', value: 1);

    expect($item)->toBeInstanceOf(Arrayable::class);
});

it('converts to array with all properties', function () {
    $item = new ItemSelected(
        search: 'john',
        label: 'John Doe',
        value: 1,
        description: 'Developer',
        image: 'https://example.com/avatar.jpg',
        icon: '<svg>...</svg>',
        additional: ['role' => 'admin'],
    );

    expect($item->toArray())->toBe([
        'search' => 'john',
        'label' => 'John Doe',
        'value' => 1,
        'description' => 'Developer',
        'image' => 'https://example.com/avatar.jpg',
        'icon' => '<svg>...</svg>',
        'additional' => ['role' => 'admin'],
    ]);
});

it('converts to array with defaults', function () {
    $item = new ItemSelected(search: 'test', label: 'Test', value: 42);

    expect($item->toArray())->toBe([
        'search' => 'test',
        'label' => 'Test',
        'value' => 42,
        'description' => null,
        'image' => null,
        'icon' => null,
        'additional' => [],
    ]);
});

it('is immutable', function () {
    $item = new ItemSelected(search: 'test', label: 'Test', value: 1);

    $reflection = new ReflectionClass($item);

    foreach ($reflection->getProperties() as $property) {
        expect($property->isReadOnly())->toBeTrue();
    }
});
