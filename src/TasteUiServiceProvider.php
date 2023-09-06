<?php

namespace src;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use TasteUi\Facades\TasteUi;
use TasteUi\View\Components\Input;

class TasteUiServiceProvider extends ServiceProvider
{
    protected const COMPONENTS = [
        'input' => Input::class,
    ];

    public function register(): void
    {
        $this->app->singleton('TasteUi', TasteUi::class);
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
