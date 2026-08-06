<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Toast\Component;
use TallStackUi\Interactions\Toast;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

afterEach(function () {
    config()->set('ts-ui.components.toast.1.position', 'top-right');
    config()->set('ts-ui.components.toast.1.stacked', false);
    config()->set('ts-ui.components.toast.1.top-on-mobile', false);

    __ts_get_component_configuration(Component::class, flush: true);
});

it('can render')
    ->expect('<x-toast />')
    ->render()
    ->toContain('tallstackui_toastBase');

it('lists every allowed position', function () {
    expect(Component::POSITIONS)->toBe([
        'top-right', 'top-left', 'top-center', 'bottom-right', 'bottom-left', 'bottom-center',
    ]);
});

it('accepts every allowed position through the fluent method', function (string $position) {
    expect((new Toast(null))->position($position))->toBeInstanceOf(Toast::class);
})->with(Component::POSITIONS);

it('rejects an unknown position through the fluent method', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid position: top-middle. Allowed: top-right, top-left, top-center, bottom-right, bottom-left, bottom-center.');

    (new Toast(null))->position('top-middle');
});

it('accepts every allowed position through the configuration', function (string $position) {
    config()->set('ts-ui.components.toast.1.position', $position);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-toast />')
        ->render()
        ->toContain("'{$position}'");
})->with(Component::POSITIONS);

it('rejects an unknown position through the configuration', function () {
    config()->set('ts-ui.components.toast.1.position', 'top-middle');
    __ts_get_component_configuration(Component::class, flush: true);

    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The [position] must be one of the following: [top-right, top-left, top-center, bottom-right, bottom-left, bottom-center]');

    expect('<x-toast />')->render();
});

it('aligns the centered positions through their own block')
    ->expect('<x-toast />')
    ->render()
    ->toContain("'md:items-center' : position.includes('-center')");

it('does not pile the toasts by default')
    ->expect('<x-toast />')
    ->render()
    ->toContain("tallstackui_toastBase(null, 'top-right', false, false, false)");

it('always renders the pile bindings, inert until stacked turns on')
    ->expect('<x-toast />')
    ->render()
    ->toContain('x-on:mouseenter="expand()"')
    ->toContain('x-on:mouseleave="collapse()"')
    ->toContain('x-on:ts-ui:toast-measured="register($event.detail)"')
    ->toContain('x-bind:style="style(index)"')
    ->toContain('x-effect="freeze(expanded)"')
    ->toContain('opacity: content(index)')
    ->toContain('contents')
    ->toContain('tallstackui_toastLoop(toast)');

it('piles the toasts when stacked is enabled', function () {
    config()->set('ts-ui.components.toast.1.stacked', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-toast />')
        ->render()
        ->toContain("tallstackui_toastBase(null, 'top-right', false, true, false)");
});

it('hands the pile its own wrapper instead of the inert one', function () {
    config()->set('ts-ui.components.toast.1.stacked', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-toast />')
        ->render()
        ->toContain('pointer-events-auto relative w-full max-w-sm')
        ->toContain('absolute inset-x-0');
});

it('does not pin the toasts to the top on mobile by default')
    ->expect('<x-toast />')
    ->render()
    ->not->toContain('max-md:justify-start');

it('pins the toasts to the top on mobile when enabled', function () {
    config()->set('ts-ui.components.toast.1.top-on-mobile', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-toast />')
        ->render()
        ->toContain('max-md:justify-start')
        ->toContain("tallstackui_toastBase(null, 'top-right', false, false, true)");
});

it('tells the pile about top-on-mobile so it can flip its anchor', function () {
    config()->set('ts-ui.components.toast.1.stacked', true);
    config()->set('ts-ui.components.toast.1.top-on-mobile', true);
    __ts_get_component_configuration(Component::class, flush: true);

    expect('<x-toast />')
        ->render()
        ->toContain("tallstackui_toastBase(null, 'top-right', false, true, true)")
        ->toContain('max-md:justify-start');
});
