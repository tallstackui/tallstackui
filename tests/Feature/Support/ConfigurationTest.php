<?php

it('replaces a scalar list instead of merging it index by index', function () {
    // Merging index by index means a published list can only grow or be swapped
    // in place, so a user could never shorten one of the defaults.
    $merged = __ts_merge_configuration(
        ['components' => ['table' => ['Foo', ['quantity' => [10, 25, 50, 100]]]]],
        ['components' => ['table' => ['Foo', ['quantity' => [15, 30]]]]],
    );

    expect(data_get($merged, 'components.table.1.quantity'))->toBe([15, 30]);
});

it('replaces a scalar list with an empty one', function () {
    $merged = __ts_merge_configuration(['environments' => ['local', 'testing', 'staging']], ['environments' => []]);

    expect($merged['environments'])->toBe([]);
});

it('keeps keys the published configuration does not mention', function () {
    $merged = __ts_merge_configuration(
        ['editor' => ['toolbar' => ['bold'], 'upload' => ['mimes' => ['image/png'], 'disk' => 'public']]],
        ['editor' => ['upload' => ['mimes' => ['image/webp']]]],
    );

    expect($merged['editor']['upload']['mimes'])->toBe(['image/webp'])
        ->and($merged['editor']['upload']['disk'])->toBe('public')
        ->and($merged['editor']['toolbar'])->toBe(['bold']);
});

it('keeps merging a list whose entries are arrays', function () {
    // The component entries are tuples of [class, options]; recursing into them
    // is what lets an outdated published file keep options added by a release.
    $merged = __ts_merge_configuration(
        ['table' => ['Foo', ['paginate' => false, 'quantity' => [10, 25]]]],
        ['table' => ['Foo', ['quantity' => [15]]]],
    );

    expect($merged['table'][1])->toBe(['paginate' => false, 'quantity' => [15]]);
});

it('replaces a scalar value with the published one', function () {
    $merged = __ts_merge_configuration(['debug' => false], ['debug' => true]);

    expect($merged['debug'])->toBeTrue();
});
