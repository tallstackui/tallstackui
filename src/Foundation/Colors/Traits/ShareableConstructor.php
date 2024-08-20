<?php

namespace TallStackUi\Foundation\Colors\Traits;

use TallStackUi\Foundation\Support\Components\ReflectComponent;

trait ShareableConstructor
{
    use SetupColors;

    public function __construct(protected object $component, protected ReflectComponent $reflect)
    {
        $this->setup();
    }
}
