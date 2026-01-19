<?php

use Illuminate\Support\Facades\Blade;
use Pest\Expectation;

uses(Tests\Browser\BrowserTestCase::class)->in('Browser');
uses(Tests\TestCase::class)->in('Feature');

expect()->extend('render', function (array $data = []): Expectation {
    /** @var Expectation $this */
    $this->value = Blade::render($this->value, $data, true); // @phpstan-ignore-line

    return $this; // @phpstan-ignore-line
});
