<?php

namespace Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TallStackUi\Facades\TallStackUi;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithViews;
    use WithWorkbench;

    /**
     * Parallel workers otherwise share a single compiled-view directory, so one
     * can delete a compiled view while another is calling filemtime() on it,
     * failing with "stat failed for ...". Paratest hands every worker a
     * TEST_TOKEN, which is enough to give each one its own directory.
     */
    protected function defineEnvironment($app): void
    {
        $token = getenv('TEST_TOKEN');

        if ($token === false || $token === '') {
            return;
        }

        $compiled = $app->basePath("storage/framework/views/parallel-{$token}");

        (new Filesystem)->ensureDirectoryExists($compiled);

        $app['config']->set('view.compiled', $compiled);
    }

    protected function getPackageAliases($app): array
    {
        return [
            'TallStackUi' => TallStackUi::class,
        ];
    }
}
