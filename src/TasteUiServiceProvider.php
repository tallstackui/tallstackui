<?php

namespace TasteUi;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class TasteUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('TasteUi', TasteUi::class);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'taste-ui');
    }

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerComponents();
    }

    public function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tasteui.php', 'tasteui');
    }

    private function registerComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, static function (BladeCompiler $blade): void {
            foreach (config('tasteui.components') as $alias => $class) {
                $blade->component($class, $alias);
            }
        });
    }
}
