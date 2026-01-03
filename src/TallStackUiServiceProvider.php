<?php

namespace TallStackUi;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TallStackUi\Console\FindComponentCommand;
use TallStackUi\Console\IdeCommand;
use TallStackUi\Console\SetupColorCommand;
use TallStackUi\Console\SetupPrefixCommand;
use TallStackUi\Personalization\PersonalizationFactory;
use TallStackUi\Support\Blade\ComponentPrefix;
use TallStackUi\Support\Blade\Directives;
use TallStackUi\View\Components\Icon;

include __DIR__.'/helpers.php';

class TallStackUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishable();

        $this->registerComponents();

        $this->registerComponentPersonalization();

        $this->registerCommands();

        Directives::register();
    }

    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton('TallStackUi', TallStackUi::class);
    }

    protected function registerCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            SetupPrefixCommand::class,
            FindComponentCommand::class,
            SetupColorCommand::class,
            IdeCommand::class,
        ]);
    }

    protected function registerComponentPersonalization(): void
    {
        // This ternary was needed to avoid exceptions when BladeUi is not installed in the base project.
        Blade::component(class_exists(\BladeUI\Icons\Components\Icon::class) ? 'BladeUI\Icons\Components\Icon' : Icon::class, 'blade-ui');

        foreach (__ts_soft_personalization_components() as $key => $class) {
            $this->app->singleton($key, fn () => new PersonalizationFactory($class));
        }
    }

    protected function registerComponents(): void
    {
        $components = config('tallstackui.components');
        $configuration = config('tallstackui.components.layout')[1];

        if ($configuration['ignore'] === true) {
            $components = array_filter(config('tallstackui.components'), fn ($component) => ! str_contains($component, 'Layout'));
        }

        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) use ($components): void {
            foreach ($components as $name => $class) {
                $class = is_array($class) ? $class[0] : $class;

                $blade->component($class, app(ComponentPrefix::class)->add($name));
            }
        });
    }

    protected function registerConfig(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'tallstack-ui');

        $this->mergeConfigFrom(__DIR__.'/config.php', 'tallstackui');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'tallstack-ui');
    }

    protected function registerPublishable(): void
    {
        $this->publishes([__DIR__.'/config.php' => config_path('tallstackui.php')], 'tallstackui.config');

        $this->publishes([__DIR__.'/lang' => lang_path('vendor/tallstack-ui')], 'tallstackui.lang');

        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/tallstack-ui')], 'tallstackui.views');
    }
}
