<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TasteUi\TasteUiServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithViews;
    use WithWorkbench;

    protected function getPackageProviders($app): array
    {
        return [
            TasteUiServiceProvider::class,
        ];
    }
}
