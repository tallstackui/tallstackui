<?php

namespace TasteUi;

use TasteUi\Support\Elements\Color;
use TasteUi\Support\Personalization;

final class TasteUi
{
    public function personalize(string $component): Personalization
    {
        return new Personalization($component);
    }

    public function colors(): Color
    {
        return new Color();
    }

    public function directives(): TasteUiDirectives
    {
        return new TasteUiDirectives();
    }
}
