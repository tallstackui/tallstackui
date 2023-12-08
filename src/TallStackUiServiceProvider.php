<?php

namespace TallStackUi;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TallStackUi\Facades\TallStackUi as Facade;
use TallStackUi\View\Personalizations\PersonalizationResources;

class TallStackUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerComponents();
        $this->registerComponentPersonalizations();
        $this->registerBladeDirectives();
    }

    public function register(): void
    {
        $this->app->singleton('TallStackUi', TallStackUi::class);

        AliasLoader::getInstance()->alias('TallStackUi', Facade::class);
    }

    public function registerComponentPersonalizations(): void
    {
        foreach (tallstackui_components_soft_personalized() as $key => $component) {
            $this->app->singleton($key, function () use ($component) {
                return new PersonalizationResources($component);
            });
        }
    }

    public function registerConfig(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'tallstack-ui');
        $this->mergeConfigFrom(__DIR__.'/contents/config.php', 'tallstackui');
        $this->loadRoutesFrom(__DIR__.'/contents/routes.php');
        $this->loadTranslationsFrom(__DIR__.'/contents/lang', 'tallstack-ui');

        $this->publishes([__DIR__.'/contents/config.php' => config_path('tallstackui.php')], 'tallstackui.config');
        $this->publishes([__DIR__.'/contents/lang' => lang_path('vendor/tallstack-ui')], 'tallstackui.lang');
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/tallstack-ui')], 'tallstackui.views');
    }

    private function registerBladeDirectives(): void
    {
        Blade::directive('tallStackUiScript', function (): string {
            $script = Facade::directives()->script();

            return "<!-- TallStackUi Scripts -->\n{$script}";
        });

        Blade::precompiler(function (string $string): string {
            return preg_replace_callback('/<\s*tallstackui\:(script)\s*\/?>/', function (array $matches): string {
                if ($matches[1] === 'script') {
                    return Facade::directives()->script();
                }
            }, $string);
        });
    }

    private function registerComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, static function (BladeCompiler $blade): void {
            foreach (config('tallstackui.components') as $alias => $class) {
                $blade->component($class, $alias);
            }
        });
    }
}
