<?php

namespace TallStackUi\Foundation\Support\Runtime;

use Illuminate\Support\ViewErrorBag;
use Livewire\Component;
use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;

class CompileRuntime
{
    public static function of(array $data, object $component, ?Component $livewire = null, ?ViewErrorBag $errors = null): array
    {
        $reflect = app(ReflectComponent::class, ['component' => $component::class]);
        $class = $reflect->attribute(PassThroughRuntime::class)?->newInstance()->runtime;

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
