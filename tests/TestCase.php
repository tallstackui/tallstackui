<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TallStackUi\Facades\TallStackUi;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithViews;
    use WithWorkbench;

    protected function getPackageAliases($app)
    {
        return [
            'TallStackUi' => TallStackUi::class,
        ];
    }
}
