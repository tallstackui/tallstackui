<?php

use Illuminate\View\ViewException;
use TallStackUi\Components\Form\Date\Component;
use Tests\TestCase;

uses(TestCase::class)->group('Feature');

it('starts the week on Sunday by default', function () {
    expect('<x-date />')->render()->toContain("0,\n     null,\n     false,\n     false)");
});

it('starts the week on the day of the start attribute', function () {
    expect('<x-date start="1" />')->render()->toContain("1,\n     null,\n     false,\n     false)");
});

it('starts the week through the global configuration', function () {
    config()->set('ts-ui.components.date.1.start', 1);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-date />')->render()->toContain("1,\n     null,\n     false,\n     false)");
    } finally {
        config()->set('ts-ui.components.date.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
});

it('lets the start attribute win over the global configuration', function () {
    config()->set('ts-ui.components.date.1.start', 1);

    __ts_get_component_configuration(Component::class, flush: true);

    try {
        expect('<x-date start="6" />')->render()->toContain("6,\n     null,\n     false,\n     false)");
    } finally {
        config()->set('ts-ui.components.date.1.start', 0);

        __ts_get_component_configuration(Component::class, flush: true);
    }
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
