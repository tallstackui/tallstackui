<?php

namespace Tests\Browser;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Laravel\Dusk\Browser;
use Livewire\Component;
use Livewire\Features\SupportTesting\Testable;
use Symfony\Component\Finder\SplFileInfo;

trait BrowserFunctions
{
    public function visit(Browser $browser, string $livewire, array $query = []): Browser|Testable
    {
        $url = '/tasteui-livewire/'.urlencode($livewire).'?'.Arr::query($query);

        return $browser->visit($url)->waitForLivewireToLoad();
    }

    protected function tasteUiUpdateConfigurations(): void
    {
        app('session')->put('_token', 'taste-ui-testing-token');
        app('config')->set('view.paths', [__DIR__.'/views', resource_path('views')]);
        config()->set('app.debug', true);
    }

    protected function tasteUiLoadComponents(): void
    {
        collect(File::allFiles(__DIR__))
            ->map(function (SplFileInfo $file) {
                return 'Tests\\Browser\\'.str($file->getRelativePathname())->before('.php')->replace('/', '\\');
            })->filter(function (string $class) {
                $exists = rescue(fn () => class_exists($class), false, false);

                return $exists && is_subclass_of($class, Component::class);
            })->each(fn (string $class) => app('livewire')->component($class));
    }

    protected function tasteUiRoutes(): void
    {
        Route::get('/tasteui-livewire/{component}', function (string $component) {
            $class = urldecode($component);

            return app()->call(new $class());
        })->middleware('web');
    }
}
