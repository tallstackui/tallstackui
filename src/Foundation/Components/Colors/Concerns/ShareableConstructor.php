<?php

namespace TallStackUi\Foundation\Components\Colors\Concerns;

use Illuminate\View\Component;
use TallStackUi\Foundation\Support\Components\ReflectComponent;

trait ShareableConstructor
{
    use SetupColors;

    public function __construct(protected Component $component, protected ReflectComponent $reflect)
    {
        $this->setup();
    }
}
