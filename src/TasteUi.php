<?php

namespace TasteUi;

use TasteUi\Support\Elements\Color;

final class TasteUi
{
    public function colors(): Color
    {
        return new Color();
    }

    public function directives(): TasteUiDirectives
    {
        return new TasteUiDirectives();
    }
}
