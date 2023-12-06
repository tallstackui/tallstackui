<?php

namespace TallStackUi;

use TallStackUi\View\Personalizations\Personalization;

class TallStackUi
{
    public function directives(): TallStackUiDirectives
    {
        return new TallStackUiDirectives();
    }

    public function personalize(?string $component = null): Personalization
    {
        return new Personalization($component);
    }
}
