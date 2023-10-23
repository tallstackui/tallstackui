<?php

namespace Tests\Browser;

use Illuminate\Http\Request;
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
        $url = '/tallstackui-livewire/'.urlencode($livewire).'?'.Arr::query($query);

        return $browser->visit($url)->waitForLivewireToLoad();
    }

    protected function tallStackUiLoadComponents(): void
    {
        collect(File::allFiles(__DIR__))
            ->map(function (SplFileInfo $file) {
                return 'Tests\\Browser\\'.str($file->getRelativePathname())->before('.php')->replace('/', '\\');
            })->filter(function (string $class) {
                $exists = rescue(fn () => class_exists($class), false, false);

                return $exists && is_subclass_of($class, Component::class);
            })->each(fn (string $class) => app('livewire')->component($class));
    }

    protected function tallStackUiRoutes(): void
    {
        Route::middleware('web')
            ->group(function () {
                Route::get('/tallstackui-livewire/{component}', function (string $component) {
                    $class = urldecode($component);

                    return app()->call(app('livewire')->new($class));
                });

                Route::get('/searchable-simple', function () {
                    return [
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
                    ];
                })->name('searchable.simple');

                Route::get('/searchable-filtered', function (Request $request) {
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
            });
    }

    protected function tallStackUiUpdateConfigurations(): void
    {
        app('session')->put('_token', 'taste-ui-testing-token');
        app('config')->set('view.paths', [__DIR__.'/views', resource_path('views')]);
        config()->set('app.env', 'testing');
        config()->set('app.debug', true);
    }
}
