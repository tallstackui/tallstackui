<?php

namespace TallStackUi;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TallStackUi\Foundation\Console\FindComponentCommand;
use TallStackUi\Foundation\Console\PersonalizationScopesCommand;
use TallStackUi\Foundation\Console\PublishColorsClassCommand;
use TallStackUi\Foundation\Console\SetupIconsCommand;
use TallStackUi\Foundation\Console\SetupPrefixCommand;
use TallStackUi\Foundation\Personalization\PersonalizationFactory;
use TallStackUi\Foundation\Support\Blade\ComponentPrefix;
use TallStackUi\Foundation\Support\Blade\Directives;

include __DIR__.'/helpers.php';

class TallStackUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerConfig();

        $this->registerComponents();

        $this->registerComponentPersonalization();

        $this->registerCommands();

        Directives::register();
    }

    public function register(): void
    {
        $this->app->singleton('TallStackUi', TallStackUi::class);
    }

    protected function registerCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            SetupIconsCommand::class,
            SetupPrefixCommand::class,
            FindComponentCommand::class,
            PublishColorsClassCommand::class,
            PersonalizationScopesCommand::class,
        ]);
    }

    protected function registerComponentPersonalization(): void
    {
        foreach (__ts_components() as $key => $class) {
            $this->app->singleton($key, fn () => new PersonalizationFactory($class));
        }
    }

    protected function registerComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade): void {
            foreach (config('tallstackui.components') as $name => $class) {
                $blade->component($class, app(ComponentPrefix::class)->add($name));
            }
        });
    }

    protected function registerConfig(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'tallstack-ui');
        $this->mergeConfigFrom(__DIR__.'/config.php', 'tallstackui');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'tallstack-ui');

        $this->publishes([__DIR__.'/config.php' => config_path('tallstackui.php')], 'tallstackui.config');
        $this->publishes([__DIR__.'/lang' => lang_path('vendor/tallstack-ui')], 'tallstackui.lang');
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/tallstack-ui')], 'tallstackui.views');
    }
}
