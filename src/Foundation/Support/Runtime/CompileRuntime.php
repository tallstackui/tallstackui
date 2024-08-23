<?php

namespace TallStackUi\Foundation\Support\Runtime;

use Illuminate\Support\ViewErrorBag;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;
use TallStackUi\Foundation\Support\Runtime\Components\ButtonRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\CheckboxRuntime;
use TallStackUi\TallStackUiComponent;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Toggle;

class CompileRuntime
{
    public static function of(array $data, TallStackUiComponent $component, bool $livewire, ?ViewErrorBag $errors = null): array
    {
        $reflect = app(ReflectComponent::class, ['component' => $component::class]);
        $parent = $reflect->parent()->name;

        $class = match (true) {
            $parent === Button::class || $parent === Circle::class => ButtonRuntime::class,
            $parent === Radio::class || $parent === Checkbox::class || $parent === Toggle::class => CheckboxRuntime::class,
            default => null,
        };

        if (! $class) {
            return [];
        }

        return app($class)->runtime(...func_get_args());
    }
}
