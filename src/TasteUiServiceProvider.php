<?php

namespace TasteUi;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TasteUi\Facades\TasteUi as Facade;
use TasteUi\Support\Personalization;

class TasteUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('TasteUi', TasteUi::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('TasteUi', Facade::class);
    }

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerComponents();
        $this->registerComponentPersonalizations();
        $this->registerBladeDirectives();
    }

    public function registerConfig(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'taste-ui');
        $this->mergeConfigFrom(__DIR__.'/../config/tasteui.php', 'tasteui');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'taste-ui');

        $this->publishes([__DIR__.'/../config/tasteui.php' => config_path('tasteui.php')], 'tasteui.config');
        $this->publishes([__DIR__.'/../lang' => lang_path('vendor/taste-ui')], 'tasteui.lang');
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/taste-ui')], 'tasteui.views');
    }

    private function registerComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, static function (BladeCompiler $blade): void {
            foreach (config('tasteui.components') as $alias => $class) {
                $blade->component($class, $alias);
            }
        });
    }

    public function registerComponentPersonalizations(): void
    {
        foreach (Personalization::COMPONENTS as $personalization => $configuration) {
            $this->app->singleton($personalization, function () use ($configuration) {
                return $this->app->make($configuration['personalize']);
            });
        }
    }

    private function registerBladeDirectives(): void
    {
        Blade::directive('tasteUiScripts', static function (?string $attributes = ''): string {
            if (! $attributes) {
                $attributes = '[]';
            }

            return "{!! TasteUi::directives()->scripts(attributes: {$attributes}) !!}";
        });

        Blade::directive('tasteUiStyles', static function (): string {
            return Facade::directives()->styles();
        });
    }
}
