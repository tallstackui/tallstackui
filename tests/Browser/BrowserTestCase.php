<?php

namespace Tests\Browser;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Dusk\Options;
use Orchestra\Testbench\Dusk\TestCase;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\TallStackUiServiceProvider;

use function Livewire\trigger;

class BrowserTestCase extends TestCase
{
    public static function tmp(): string
    {
        return __DIR__.'/tmp/';
    }

    public static function tweakApplicationHook(): Closure
    {
        return function () {};
    }

    protected function clean(): void
    {
        Artisan::call('view:clear');

        File::deleteDirectory(self::tmp());
        File::deleteDirectory($this->livewireViewsPath());
        File::deleteDirectory($this->livewireClassesPath());
        File::deleteDirectory($this->livewireTestsPath());
        File::delete(app()->bootstrapPath('cache/livewire-components.php'));

        File::ensureDirectoryExists(self::tmp());
    }

    protected function defineEnvironment($app): void
    {
        tap($app['session'], function ($session) {
            $session->put('_token', str()->random(40));
        });

        tap($app['config'], function (Repository $config) {
            $config->set('app.env', 'testing');
            $config->set('app.debug', true);
            $config->set('view.paths', [__DIR__.'/views', resource_path('views')]);
            $config->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');
            $config->set('database.default', 'testbench');
            $config->set('database.connections.testbench', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            $config->set('filesystems.disks.tmp-for-tests', [
                'driver' => 'local',
                'root' => self::tmp(),
            ]);
            $config->set('cache.default', 'array');
        });
    }

    /** @param  Router  $router */
    protected function defineWebRoutes($router): void
    {
        $router->get('/searchable-simple', fn () => [
            [
                'label' => 'delectus aut autem',
                'value' => 'delectus aut autem',
            ],
            [
                'label' => 'quis ut nam facilis et officia qui',
                'value' => 'quis ut nam facilis et officia qui',
            ],
            [
                'label' => 'fugiat veniam minus',
                'value' => 'fugiat veniam minus',
            ],
            [
                'label' => 'et porro tempora',
                'value' => 'et porro tempora',
            ],
            [
                'label' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
                'value' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
            ],
        ])->name('searchable.simple');

        $router->get('/searchable-filtered', function (Request $request) {
            $options = collect([
                [
                    'label' => 'delectus aut autem',
                    'value' => 'delectus aut autem',
                ],
                [
                    'label' => 'quis ut nam facilis et officia qui',
                    'value' => 'quis ut nam facilis et officia qui',
                ],
                [
                    'label' => 'fugiat veniam minus',
                    'value' => 'fugiat veniam minus',
                ],
                [
                    'label' => 'et porro tempora',
                    'value' => 'et porro tempora',
                ],
                [
                    'label' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
                    'value' => 'laboriosam mollitia et enim quasi adipisci quia provident illum',
                ],
            ]);

            if (! $request->has('search')) {
                return $options;
            }

            return [
                [
                    'label' => 'et porro tempora',
                    'value' => 'et porro tempora',
                ],
            ];
        })->name('searchable.filtered');
    }

    protected function getApplicationTimezone($app): string
    {
        return (bool) getenv('GITHUB_ACTIONS') === false ? 'America/Sao_Paulo' : $app['config']['app.timezone'];
    }

    protected function getPackageAliases($app): array
    {
        return ['TallStackUi' => TallStackUi::class];
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            TallStackUiServiceProvider::class,
        ];
    }

    protected function livewireClassesPath($path = ''): string
    {
        return app_path('Livewire'.($path ? '/'.$path : ''));
    }

    protected function livewireTestsPath($path = ''): string
    {
        return base_path('tests/Feature/Livewire'.($path ? '/'.$path : ''));
    }

    protected function livewireViewsPath($path = ''): string
    {
        return resource_path('views').'/livewire'.($path ? '/'.$path : '');
    }

    protected function paused(int $seconds = 3): int
    {
        return 1000 * $seconds;
    }

    protected function setUp(): void
    {
        if (isset($_SERVER['CI'])) {
            Options::withoutUI();
        }

        $this->afterApplicationCreated(fn () => $this->clean());

        $this->beforeApplicationDestroyed(fn () => $this->clean());

        parent::setUp();

        trigger('browser.testCase.setUp', $this);
    }

    protected function skipOnGitHubActions(?string $message = null): void
    {
        if ((bool) getenv('GITHUB_ACTIONS') === false) {
            return;
        }

        $this->markTestSkipped($message ?? 'For some unknown reason this test fails on GitHub Actions.');
    }

    protected function tearDown(): void
    {
        trigger('browser.testCase.tearDown', $this);

        if (! $this->status()->isSuccess()) {
            $this->captureFailuresFor(collect(static::$browsers));
            $this->storeSourceLogsFor(collect(static::$browsers));
        }

        $this->closeAll();

        parent::tearDown();
    }
}
