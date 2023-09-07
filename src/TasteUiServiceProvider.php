<?php

namespace TasteUi;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TasteUi\View\Components\Form\Input;
use TasteUi\View\Components\Form\Label;
use TasteUi\View\Components\Icon;

class TasteUiServiceProvider extends ServiceProvider
{
    protected const COMPONENTS = [
        'input' => Input::class,
        'icon'  => Icon::class,
        'label' => Label::class,
    ];

    public function register(): void
    {
        $this->app->singleton('TasteUi', TasteUi::class);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'taste-ui');
    }

    public function boot(): void
    {
        $this->registerComponents();
    }

    private function registerComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, static function (BladeCompiler $blade): void {
            foreach (self::COMPONENTS as $alias => $class) {
                $blade->component($class, $alias);
            }
        });
    }
}
