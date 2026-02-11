<?php

use TallStackUi\Support\Breadcrumbs\BreadcrumbRegistry;
use TallStackUi\Support\Breadcrumbs\BreadcrumbTrail;

it('returns empty array initially', function () {
    $trail = new BreadcrumbTrail;

    expect($trail->items())->toBe([]);
});

it('can add item with label only', function () {
    $trail = new BreadcrumbTrail;
    $trail->add('Home');

    expect($trail->items())->toHaveCount(1)
        ->and($trail->items()[0])->toHaveKey('label', 'Home');
});

it('can add item with label and link', function () {
    $trail = new BreadcrumbTrail;
    $trail->add('Home', '/');

    expect($trail->items()[0])
        ->toHaveKey('label', 'Home')
        ->toHaveKey('link', '/');
});

it('can add item with all properties', function () {
    $trail = new BreadcrumbTrail;
    $trail->add('Settings', '/settings', 'cog', 'Go to settings');

    $item = $trail->items()[0];

    expect($item)
        ->toHaveKey('label', 'Settings')
        ->toHaveKey('link', '/settings')
        ->toHaveKey('icon', 'cog')
        ->toHaveKey('tooltip', 'Go to settings');
});

it('filters null values from items', function () {
    $trail = new BreadcrumbTrail;
    $trail->add('Home', null);

    expect($trail->items()[0])
        ->toHaveKey('label')
        ->not->toHaveKey('link');
});

it('has null parent route initially', function () {
    $trail = new BreadcrumbTrail;

    expect($trail->parentRoute())->toBeNull();
});

it('can set and retrieve parent route', function () {
    $trail = new BreadcrumbTrail;
    $trail->parent('dashboard');

    expect($trail->parentRoute())->toBe('dashboard');
});

it('add method is chainable', function () {
    $trail = new BreadcrumbTrail;

    expect($trail->add('Home'))->toBeInstanceOf(BreadcrumbTrail::class);
});

// BreadcrumbRegistry

it('has returns false for unregistered route', function () {
    $registry = new BreadcrumbRegistry;

    expect($registry->has('unknown.route'))->toBeFalse();
});

it('can register a definition and has returns true', function () {
    $registry = new BreadcrumbRegistry;
    $registry->for('dashboard', fn (BreadcrumbTrail $trail) => $trail->add('Dashboard'));

    expect($registry->has('dashboard'))->toBeTrue();
});

it('for method is chainable', function () {
    $registry = new BreadcrumbRegistry;

    expect($registry->for('home', fn (BreadcrumbTrail $trail) => $trail->add('Home')))
        ->toBeInstanceOf(BreadcrumbRegistry::class);
});

it('resolve returns empty array for unknown route', function () {
    $registry = new BreadcrumbRegistry;

    expect($registry->resolve('unknown.route'))->toBe([]);
});

it('resolve returns empty array when route is null', function () {
    $registry = new BreadcrumbRegistry;

    expect($registry->resolve(null))->toBe([]);
});
