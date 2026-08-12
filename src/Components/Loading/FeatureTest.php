<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Loading\Component;
use TallStackUi\Components\Spinner\Component as Spinner;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

beforeEach(function () {
    livewireContext();
});

afterEach(function () {
    config()->set('ts-ui.components.loading.1.indicator', null);
    config()->set('ts-ui.components.spinner.1.type', 'ring');

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render the default svg indicator')
    ->expect('<x-loading />')
    ->render()
    ->toContain('h-12 w-12 animate-spin text-primary-700')
    ->not->toContain('dusk="spinner-');

it('can render a spinner indicator')
    ->expect('<x-loading indicator="spinner.bars" />')
    ->render()
    ->toContain('dusk="spinner-bars"')
    ->not->toContain('h-12 w-12 animate-spin text-primary-700');

it('can render the spinner default when the type is omitted')
    ->expect('<x-loading indicator="spinner" />')
    ->render()
    ->toContain('dusk="spinner-ring"');

it('can render spinner indicator variants', function (string $type) {
    $component = <<<HTML
    <x-loading indicator="spinner.$type" />
    HTML;

    expect($component)->render()->toContain('dusk="spinner-'.$type.'"');
})->with(array_values(array_diff(Spinner::TYPES, ['shimmer', 'caret'])));

it('cannot use shimmer or caret as an indicator', function (string $type) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('animates its own text');

    $component = <<<HTML
    <x-loading indicator="spinner.$type" />
    HTML;

    expect($component)->render();
})->with(['shimmer', 'caret']);

it('can use the global indicator configuration', function () {
    config()->set('ts-ui.components.loading.1.indicator', 'spinner.dots');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-loading />')->render()->toContain('dusk="spinner-dots"');
});

it('can use the global spinner type when the indicator is spinner', function () {
    config()->set('ts-ui.components.loading.1.indicator', 'spinner');
    config()->set('ts-ui.components.spinner.1.type', 'wave');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-loading />')->render()->toContain('dusk="spinner-wave"');
});

it('can override the global indicator configuration inline', function () {
    config()->set('ts-ui.components.loading.1.indicator', 'spinner.dots');

    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-loading indicator="spinner.bars" />')
        ->render()
        ->toContain('dusk="spinner-bars"')
        ->not->toContain('dusk="spinner-dots"');
});

it('cannot use an invalid global indicator configuration', function () {
    config()->set('ts-ui.components.loading.1.indicator', 'spinner.foo');

    __ts_get_component_configuration(Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [indicator] must be [spinner] or [spinner.{type}]');

    expect('<x-loading />')->render();
});

it('cannot use an invalid indicator', function (string $indicator) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [indicator] must be [spinner] or [spinner.{type}]');

    $component = <<<HTML
    <x-loading indicator="$indicator" />
    HTML;

    expect($component)->render();
})->with([
    'bars',
    'spinner.foo',
    'icon.loading',
    'spinner.',
]);

it('keeps custom text instead of the indicator')
    ->expect('<x-loading indicator="spinner.bars" text="Please wait" />')
    ->render()
    ->toContain('Please wait')
    ->not->toContain('dusk="spinner-bars"');

it('keeps the slot instead of the indicator')
    ->expect('<x-loading indicator="spinner.bars"><span>Hold on</span></x-loading>')
    ->render()
    ->toContain('Hold on')
    ->not->toContain('dusk="spinner-bars"');
