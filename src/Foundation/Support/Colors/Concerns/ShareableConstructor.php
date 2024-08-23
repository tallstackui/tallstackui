<?php

namespace TallStackUi\Foundation\Support\Colors\Concerns;

use Illuminate\View\Component;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;

trait ShareableConstructor
{
    use SetupColors;

    public function __construct(protected Component $component, protected ReflectComponent $reflect)
    {
        $this->setup();
    }
}
