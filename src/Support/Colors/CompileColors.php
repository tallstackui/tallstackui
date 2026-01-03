<?php

namespace TallStackUi\Support\Colors;

use Exception;
use Illuminate\View\Component;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Support\Miscellaneous\ReflectComponent;

/**
 * @internal
 */
class CompileColors
{
    /** @throws Exception */
    public static function of(Component $component): ?array
    {
        $reflect = app(ReflectComponent::class, ['component' => $component::class]);
        $class = $reflect->attribute(ColorsThroughOf::class)?->newInstance()->class;

        if (! $class) {
            return null;
        }

        return app($class, ['component' => $component, 'reflect' => $reflect])->colors();
    }
}
