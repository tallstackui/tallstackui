<?php

namespace TallStackUi\View\Personalizations\Support\Colors;

use TallStackUi\View\Components\Button\Traits\DefaultButtonColorClasses;

class ButtonColors
{
    use DefaultButtonColorClasses;

    public function __invoke(): array
    {
        return $this->tallStackUiButtonsColors();
    }
}
