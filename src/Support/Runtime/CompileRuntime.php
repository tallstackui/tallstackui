<?php

namespace TallStackUi\Support\Runtime;

use Illuminate\Contracts\View\Factory;
use ReflectionException;
use TallStackUi\Attributes\PassThroughRuntime;
use TallStackUi\Support\Miscellaneous\ReflectComponent;
use TallStackUi\TallStackUiComponent;

// The main purpose of this class, the classes inside the Components/ folder and
// the PassThroughRuntime attribute is to allow us to define variables at runtime
// so that the components contain only one @php tag at the top of the file with
// $customization = $classes() instead of having to do several steps to define
// variables. To make this happen, it was necessary to rename the names of
// certain variables used in Blade only, since when we merge, we do not replace
// the original variables but rather create new ones.
class CompileRuntime
{
    /** @throws ReflectionException */
    public static function of(
        TallStackUiComponent $component,
        Factory $factory,
        array $data,
        array $shared,
    ): array {
        $reflect = app(ReflectComponent::class, ['component' => $component::class]);
        $class = $reflect->attribute(PassThroughRuntime::class)?->newInstance()->runtime;

        if (! $class) {
            return [];
        }

        return app($class, [
            'component' => $component,
            'data' => $data,
            'factory' => $factory,
            'livewire' => $shared['__livewire'] ?? null,
            'errors' => $shared['errors'] ?? null,
        ])->runtime();
    }
}
