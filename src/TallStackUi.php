<?php

namespace TallStackUi;

use TallStackUi\View\Personalizations\Personalization;
use TallStackUi\View\Personalizations\Support\Color;

class TallStackUi
{
    public function colors(): Color
    {
        return new Color();
    }

    public function directives(): TallStackUiDirectives
    {
        return new TallStackUiDirectives();
    }

    public function personalize(string $component = null): Personalization
    {
        return new Personalization($component);
    }
}
