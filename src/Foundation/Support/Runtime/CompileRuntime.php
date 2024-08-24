<?php

namespace TallStackUi\Foundation\Support\Runtime;

use Illuminate\Support\ViewErrorBag;
use Livewire\Component;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;
use TallStackUi\Foundation\Support\Runtime\Components\ButtonRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\CheckboxRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\ColorRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\DateRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\InputRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\LabelRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\NumberRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\PasswordRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\RangeRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\SelectNativeRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\StatsRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\TextareaRuntime;
use TallStackUi\Foundation\Support\Runtime\Components\TooltipRuntime;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Date;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Label;
use TallStackUi\View\Components\Form\Number;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Range;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Select\Native;
use TallStackUi\View\Components\Stats;
use TallStackUi\View\Components\Tooltip;

class CompileRuntime
{
    public static function of(array $data, object $component, ?Component $livewire = null, ?ViewErrorBag $errors = null): array
    {
        $reflect = app(ReflectComponent::class, ['component' => $component::class]);
        $parent = $reflect->parent()->name;

        // TODO: order using alphabetical order
        $class = match (true) {
            $parent === Password::class => PasswordRuntime::class,
            $parent === Number::class => NumberRuntime::class,
            $parent === Label::class => LabelRuntime::class,
            $parent === Date::class => DateRuntime::class,
            $parent === Tooltip::class => TooltipRuntime::class,
            $parent === Stats::class => StatsRuntime::class,
            $parent === Native::class => SelectNativeRuntime::class,
            $parent === Textarea::class => TextareaRuntime::class,
            $parent === Range::class => RangeRuntime::class,
            $parent === Input::class => InputRuntime::class,
            $parent === Color::class => ColorRuntime::class,
            $parent === Button::class || $parent === Circle::class => ButtonRuntime::class,
            $parent === Radio::class || $parent === Checkbox::class || $parent === Toggle::class => CheckboxRuntime::class,
            default => null,
        };

        if (! $class) {
            return [];
        }

        return app($class, [
            'data' => $data,
            'component' => $component,
            'livewire' => $livewire,
            'errors' => $errors,
        ])->runtime();
    }
}
