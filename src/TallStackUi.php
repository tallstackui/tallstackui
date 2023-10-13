<?php

namespace TallStackUi;

use TallStackUi\Support\Elements\Color;
use TallStackUi\Support\Personalization;

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
