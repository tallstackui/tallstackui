<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

// The table requires the Livewire context, which the skeleton deliberately
// waives so it can be drawn inside the placeholder of a #[Lazy] component.

dataset('table.headers', [
    [[['index' => 'name', 'label' => 'Name'], ['index' => 'email', 'label' => 'E-mail']]],
]);

it('can render the skeleton outside the livewire context')
    ->expect('<x-table skeleton />')
    ->render()
    ->toContain('animate-pulse');

it('can render the skeleton keeping the real header labels', function (array $headers) {
    expect('<x-table skeleton :$headers />')
        ->render(['headers' => $headers])
        ->toContain('Name')
        ->toContain('E-mail');
})->with('table.headers');

it('can render the skeleton deriving the columns from the headers', function (array $headers) {
    $html = Blade::render('<x-table skeleton="3" :$headers />', ['headers' => $headers]);

    expect(substr_count($html, 'bg-gray-200'))->toBe(6);
})->with('table.headers');

it('can render the skeleton falling back to four columns without headers', function () {
    $html = Blade::render('<x-table skeleton="2" />');

    expect(substr_count($html, 'bg-gray-200'))->toBe(12);
});

it('can render the skeleton with the selectable column', function (array $headers) {
    $html = Blade::render('<x-table skeleton="2" selectable :$headers />', ['headers' => $headers]);

    expect(substr_count($html, 'bg-gray-200'))->toBe(7);
})->with('table.headers');

it('can render the skeleton with the expandable column', function (array $headers) {
    $html = Blade::render('<x-table skeleton="2" expandable :$headers />', ['headers' => $headers]);

    expect(substr_count($html, 'bg-gray-200'))->toBe(6);
})->with('table.headers');

it('can render the skeleton with the filter bar')
    ->expect('<x-table skeleton filter />')
    ->render()
    ->toContain('h-9');

it('can render the skeleton without the filter bar')
    ->expect('<x-table skeleton />')
    ->render()
    ->not->toContain('h-9');

it('can render the skeleton with the pagination footer')
    ->expect('<x-table skeleton paginate />')
    ->render()
    ->toContain('h-8 w-64');

it('cannot render the skeleton with a count below one', function () {
    $this->expectException(ViewException::class);

    expect('<x-table skeleton="0" />')->render();
});
