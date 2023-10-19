<?php

namespace TallStackUi;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TallStackUi\Facades\TallStackUi as Facade;
use TallStackUi\View\Personalizations\Personalization;
use TallStackUi\View\Personalizations\Providers\ConfigurationProvider;

class TallStackUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerComponents();
        $this->registerComponentPersonalizations();
        $this->registerBladeDirectives();

        ConfigurationProvider::generals();
    }

    public function register(): void
    {
        $this->app->singleton('TallStackUi', TallStackUi::class);

        AliasLoader::getInstance()->alias('TallStackUi', Facade::class);
    }

    public function registerComponentPersonalizations(): void
    {
        foreach (Personalization::PERSONALIZABLES as $personalization => $configuration) {
            $this->app->singleton($personalization, function () use ($configuration) {
                return $this->app->make($configuration);
            });
        }
    }

    public function registerConfig(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tallstack-ui');
        $this->mergeConfigFrom(__DIR__.'/config/tallstackui.php', 'tallstackui');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'tallstack-ui');

        $this->publishes([__DIR__.'/config/tallstackui.php' => config_path('tallstackui.php')], 'tallstackui.config');
        $this->publishes([__DIR__.'/../lang' => lang_path('vendor/tallstack-ui')], 'tallstackui.lang');
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/tallstack-ui')], 'tallstackui.views');
    }

    private function registerBladeDirectives(): void
    {
        Blade::directive('tallStackUiScripts', static function (): string {
            $scripts = Facade::directives()->scripts();

            return "<!-- TallStackUi Scripts -->\n{$scripts}";
        });

        Blade::directive('tallStackUiStyles', static function (): string {
            $styles = Facade::directives()->styles();

            return "<!-- TallStackUi Styles -->\n{$styles}";
        });

        Blade::precompiler(static function (string $string): string {
            $pattern = '/<\s*tallstackui\:(setup)\s*\/?>/';

            return preg_replace_callback($pattern, function () {
                $scripts = Facade::directives()->scripts();

                return "<!-- TallStackUi Setup -->\n{$scripts}";
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
