<?php

namespace TallStackUi;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TallStackUi\Components\Icon\Component as Icon;
use TallStackUi\Console\FindComponentCommand;
use TallStackUi\Console\IdeCommand;
use TallStackUi\Console\SetupColorCommand;
use TallStackUi\Console\SetupPrefixCommand;
use TallStackUi\Customization\CustomizationFactory;
use TallStackUi\Support\Blade\ComponentPrefix;
use TallStackUi\Support\Blade\Directives;
use TallStackUi\Support\Breadcrumbs\BreadcrumbRegistry;

include __DIR__.'/helpers.php';

class TallStackUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishable();

        $this->registerComponents();

        $this->registerComponentCustomization();

        $this->registerCommands();

        $this->registerBreadcrumbs();

        Directives::register();
    }

    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton('TallStackUi', TallStackUi::class);

        $this->app->singleton(BreadcrumbRegistry::class);
    }

    protected function registerBreadcrumbs(): void
    {
        $configuration = __ts_get_component_configuration(Components\Breadcrumbs\Component::class);

        if (! $configuration || empty($configuration['files'])) {
            return;
        }

        foreach ($configuration['files'] as $path) {
            $file = base_path($path);

            if (file_exists($file)) {
                require $file;
            }
        }
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

    protected function registerComponentCustomization(): void
    {
        // This ternary was needed to avoid exceptions when BladeUi is not installed in the base project.
        Blade::component(class_exists(\BladeUI\Icons\Components\Icon::class) ? 'BladeUI\Icons\Components\Icon' : Icon::class, 'blade-ui');

        foreach (__ts_soft_customization_components() as $key => $class) {
            $this->app->singleton($key, fn () => new CustomizationFactory($class));
        }
    }

    protected function registerComponents(): void
    {
        $components = config('ts-ui.components');
        $configuration = config('ts-ui.components.layout')[1];

        if ($configuration['ignore'] === true) {
            $components = array_filter(config('ts-ui.components'), fn (string|array $component) => ! str_contains(is_array($component) ? $component[0] : $component, 'Layout'));
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ts-ui');

        $this->mergeConfigFrom(__DIR__.'/config.php', 'ts-ui');

        // The config is published as 'tallstackui' but used internally as 'ts-ui'.
        // We merge published values so user customizations take effect.
        if ($published = config('tallstackui')) {
            config(['ts-ui' => array_replace_recursive(config('ts-ui'), $published)]);
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ts-ui');
    }

    protected function registerPublishable(): void
    {
        $this->publishes([__DIR__.'/config.php' => config_path('tallstackui.php')], 'tallstackui.config');

        $this->publishes([__DIR__.'/../lang' => lang_path('vendor/ts-ui')], 'tallstackui.lang');

        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/ts-ui')], 'tallstackui.views');

        $this->publishes([__DIR__.'/Support/Breadcrumbs/stubs/breadcrumbs.php.stub' => base_path('routes/breadcrumbs.php')], 'tallstackui.breadcrumbs');
    }
}
