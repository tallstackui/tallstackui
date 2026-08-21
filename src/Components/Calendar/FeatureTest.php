<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Calendar\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('renders an inline calendar without an input field', function () {
    expect('<x-calendar />')->render()
        ->toContain('tallstackui_calendar')
        ->not->toContain('<input type="text"');
});

it('renders with label', function () {
    expect('<x-calendar label="Pick a date" />')->render()->toContain('Pick a date');
});

it('renders with hint', function () {
    expect('<x-calendar hint="Format YYYY-MM-DD" />')->render()->toContain('Format YYYY-MM-DD');
});

it('renders range mode', function () {
    expect('<x-calendar range />')->render()->toContain("true,\n     false,\n     false,");
});

it('renders double mode when range is set', function () {
    expect('<x-calendar range double />')->render()->toContain("true,\n     false,\n     true,");
});

it('throws when double is set without range', function () {
    $this->expectException(ViewException::class);

    expect('<x-calendar double />')->render();
});

it('throws when range and multiple are both set', function () {
    $this->expectException(ViewException::class);

    expect('<x-calendar range multiple />')->render();
});

it('does not apply locked styles by default', function () {
    expect('<x-calendar />')->render()->not->toContain('pointer-events-none');
});

it('applies locked styles to month and year buttons when lock-month-year is set', function () {
    expect('<x-calendar lock-month-year />')->render()
        ->toContain('pointer-events-none')
        ->toContain('cursor-default');
});

it('throws when lock-month-year and month-year-only are both set', function () {
    $this->expectException(ViewException::class);

    expect('<x-calendar lock-month-year month-year-only />')->render();
});

it('renders with shadow and without border by default', function () {
    expect('<x-calendar />')->render()
        ->toContain('shadow-md')
        ->not->toContain('shadow-none!')
        ->not->toContain('border border-gray-200');
});

it('renders shadowless', function () {
    expect('<x-calendar shadowless />')->render()->toContain('shadow-none!');
});

it('renders bordered', function () {
    expect('<x-calendar bordered />')->render()->toContain('border border-gray-200 dark:border-dark-700');
});

it('renders shadowless and bordered together', function () {
    expect('<x-calendar shadowless bordered />')->render()
        ->toContain('shadow-none!')
        ->toContain('border border-gray-200 dark:border-dark-700');
});

it('renders the flat look through the global configuration', function () {
    config()->set('ts-ui.components.calendar.1.shadowless', true);
    config()->set('ts-ui.components.calendar.1.bordered', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-calendar />')->render()
            ->toContain('shadow-none!')
            ->toContain('border border-gray-200 dark:border-dark-700');
    } finally {
        config()->set('ts-ui.components.calendar.1.shadowless', false);
        config()->set('ts-ui.components.calendar.1.bordered', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('lets the flat look props win over the global configuration', function () {
    config()->set('ts-ui.components.calendar.1.shadowless', true);
    config()->set('ts-ui.components.calendar.1.bordered', true);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-calendar :shadowless="false" :bordered="false" />')->render()
            ->not->toContain('shadow-none!')
            ->not->toContain('border border-gray-200 dark:border-dark-700');
    } finally {
        config()->set('ts-ui.components.calendar.1.shadowless', false);
        config()->set('ts-ui.components.calendar.1.bordered', false);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('starts the week on Sunday by default', function () {
    expect('<x-calendar />')->render()->toContain("0,\n     null,\n     false,\n     false)");
});

it('starts the week through the global configuration', function () {
    config()->set('ts-ui.components.calendar.1.start', 1);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-calendar />')->render()->toContain("1,\n     null,\n     false,\n     false)");
    } finally {
        config()->set('ts-ui.components.calendar.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('lets the start attribute win over the global configuration', function () {
    config()->set('ts-ui.components.calendar.1.start', 1);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-calendar start="6" />')->render()->toContain("6,\n     null,\n     false,\n     false)");
    } finally {
        config()->set('ts-ui.components.calendar.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('cannot start the week on a negative day', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Calendar: The [start] attribute must be between 0 and 6.');

    expect('<x-calendar start="-1" />')->render();
});

it('cannot start the week out of range through the global configuration', function () {
    config()->set('ts-ui.components.calendar.1.start', 7);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('[TallStackUI] Calendar: The [start] attribute must be between 0 and 6.');

        expect('<x-calendar />')->render();
    } finally {
        config()->set('ts-ui.components.calendar.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});
