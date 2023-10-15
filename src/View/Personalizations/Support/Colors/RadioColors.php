<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Form\Traits\DefaultInputClasses;

class RadioColors
{
    use DefaultInputClasses;

    public function __invoke(): array
    {
        return ['input.color' => $this->radioColors()];
    }
}
