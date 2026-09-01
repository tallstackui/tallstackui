<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Date\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('starts the week on Sunday by default', function () {
    expect('<x-date />')->render()->toContain("0,\n     null,\n     false,\n     false,\n     false)");
});

it('starts the week on the day of the start attribute', function () {
    expect('<x-date start="1" />')->render()->toContain("1,\n     null,\n     false,\n     false,\n     false)");
});

it('starts the week through the global configuration', function () {
    config()->set('ts-ui.components.date.1.start', 1);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-date />')->render()->toContain("1,\n     null,\n     false,\n     false,\n     false)");
    } finally {
        config()->set('ts-ui.components.date.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('lets the start attribute win over the global configuration', function () {
    config()->set('ts-ui.components.date.1.start', 1);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-date start="6" />')->render()->toContain("6,\n     null,\n     false,\n     false,\n     false)");
    } finally {
        config()->set('ts-ui.components.date.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('can render typeable', function () {
    expect('<x-date typeable />')->render()
        ->toContain("false,\n     true)")
        ->not->toContain('cursor-pointer', 'caret-transparent');
});

it('keeps the picker-only input by default', function () {
    expect('<x-date />')->render()
        ->toContain('cursor-pointer', 'caret-transparent');
});

it('cannot use typeable with range', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Date: The [typeable] cannot be used with [range], [multiple] or [month-year-only].');

    expect('<x-date range typeable />')->render();
});

it('cannot use typeable with multiple', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Date: The [typeable] cannot be used with [range], [multiple] or [month-year-only].');

    expect('<x-date multiple typeable />')->render();
});

it('cannot use typeable with month-year-only', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Date: The [typeable] cannot be used with [range], [multiple] or [month-year-only].');

    expect('<x-date month-year-only typeable />')->render();
});

it('cannot start the week on a negative day', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('[TallStackUI] Form\Date: The [start] attribute must be between 0 and 6.');

    expect('<x-date start="-1" />')->render();
});

it('cannot start the week out of range through the global configuration', function () {
    config()->set('ts-ui.components.date.1.start', 7);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('[TallStackUI] Form\Date: The [start] attribute must be between 0 and 6.');

        expect('<x-date />')->render();
    } finally {
        config()->set('ts-ui.components.date.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});
