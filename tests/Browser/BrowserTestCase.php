<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Dusk\Options;
use Orchestra\Testbench\Dusk\TestCase;
use TasteUi\TasteUiServiceProvider;

class BrowserTestCase extends TestCase
{
    use BrowserFunctions;

    protected function setUp(): void
    {
        if (isset($_SERVER['CI'])) {
            Options::withoutUI();
        }

        $this->afterApplicationCreated(fn () => $this->tasteUiClearState());
        $this->beforeApplicationDestroyed(fn () => $this->tasteUiClearState());

        parent::setUp();

        $this->tweakApplication(function () {
            $testCase = new self('browser');
            $testCase->tasteUiLoadComponents();
            $testCase->tasteUiRoutes();
            $testCase->tasteUiUpdateConfigurations();
        });
    }

    protected function tearDown(): void
    {
        $this->removeApplicationTweaks();
        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            TasteUiServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('view.paths', [__DIR__.'/views', resource_path('views')]);
        $app['config']->set('app.key', 'base64:bMQdVAbryqTAZYxrYqTplHFRv9JqKTaYEVwwrLsGo4Y=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    private function livewireClassesPath($path = ''): string
    {
        return app_path('Livewire'.($path ? '/'.$path : ''));
    }

    private function livewireViewsPath($path = ''): string
    {
        return resource_path('views').'/livewire'.($path ? '/'.$path : '');
    }

    private function livewireTestsPath($path = ''): string
    {
        return base_path('tests/Feature/Livewire'.($path ? '/'.$path : ''));
    }

    private function tasteUiClearState(): void
    {
        Artisan::call('view:clear');

        File::deleteDirectory($this->livewireViewsPath());
        File::deleteDirectory($this->livewireClassesPath());
        File::deleteDirectory($this->livewireTestsPath());
        File::delete(app()->bootstrapPath('cache/livewire-components.php'));
    }
}
