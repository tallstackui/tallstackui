<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\BackToTop\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('can render')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('tallstackui_backToTop');

it('can render with bottom-left position')
    ->expect('<x-back-to-top position="bottom-left" />')
    ->render()
    ->toContain('fixed')
    ->toContain('left-6');

it('can render with bottom-right position')
    ->expect('<x-back-to-top position="bottom-right" />')
    ->render()
    ->toContain('fixed')
    ->toContain('right-6');

it('cannot render with invalid position', function () {
    $this->expectException(ViewException::class);

    expect('<x-back-to-top position="top-right" />')->render();
});

it('can render with xs size')
    ->expect('<x-back-to-top xs />')
    ->render()
    ->toContain('h-8 w-8');

it('can render with sm size')
    ->expect('<x-back-to-top sm />')
    ->render()
    ->toContain('h-10 w-10');

it('can render with md size')
    ->expect('<x-back-to-top md />')
    ->render()
    ->toContain('h-12 w-12');

it('can render with lg size')
    ->expect('<x-back-to-top lg />')
    ->render()
    ->toContain('h-14 w-14');

it('can render with default md size')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('h-12 w-12');

it('can render with square shape')
    ->expect('<x-back-to-top square />')
    ->render()
    ->toContain('rounded-lg')
    ->not->toContain('rounded-full');

it('can render with round shape by default')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('rounded-full');

it('can render with custom icon')
    ->expect('<x-back-to-top icon="arrow-up" />')
    ->render()
    ->toContain('<svg');

it('can render with anchor')
    ->expect('<x-back-to-top anchor="#hero" />')
    ->render()
    ->toContain('#hero');

it('can render with immediate scroll')
    ->expect('<x-back-to-top immediate />')
    ->render()
    ->toContain('false');

it('can render with smooth scroll by default')
    ->expect('<x-back-to-top />')
    ->render()
    ->toContain('true');

it('cannot render with invalid size through the global configuration', function () {
    config()->set('ts-ui.components.back-to-top.1.size', 'xxl');

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        $this->expectException(ViewException::class);

        expect('<x-back-to-top />')->render();
    } finally {
        config()->set('ts-ui.components.back-to-top.1.size', 'md');

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can render the global configuration defaults', function (string $key, mixed $value, string $expected) {
    config()->set("ts-ui.components.back-to-top.1.{$key}", $value);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-back-to-top />')->render()->toContain($expected);
    } finally {
        __ts_get_component_configuration(Component::class, flush: true);
    }
})->with([
    'immediate' => ['immediate', true, 'tallstackui_backToTop(null, false)'],
    'square' => ['square', true, 'rounded-lg'],
    'color' => ['color', 'red', 'bg-red-500'],
    'icon' => ['icon', 'arrow-up', 'M11.47 2.47'],
    'position' => ['position', 'bottom-left', 'left-6'],
    'size' => ['size', 'lg', 'h-14 w-14'],
]);

it('can let the props win over the global configuration', function (string $key, mixed $value, string $component, string $expected) {
    config()->set("ts-ui.components.back-to-top.1.{$key}", $value);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect($component)->render()->toContain($expected);
    } finally {
        __ts_get_component_configuration(Component::class, flush: true);
    }
})->with([
    'immediate' => ['immediate', true, '<x-back-to-top :immediate="false" />', 'tallstackui_backToTop(null, true)'],
    'square' => ['square', true, '<x-back-to-top :square="false" />', 'rounded-full'],
    'color' => ['color', 'red', '<x-back-to-top color="green" />', 'bg-green-500'],
    'icon' => ['icon', 'arrow-up', '<x-back-to-top icon="chevron-up" />', 'M11.47 7.72'],
    'position' => ['position', 'bottom-left', '<x-back-to-top position="bottom-right" />', 'right-6'],
    'size' => ['size', 'lg', '<x-back-to-top xs />', 'h-8 w-8'],
]);

it('can render with colors', function (string $colors) {
    $component = <<<HTML
    <x-back-to-top color="$colors" />
    HTML;

    expect($component)
        ->render()
        ->toContain('tallstackui_backToTop');
})->with(colorsDataset());
