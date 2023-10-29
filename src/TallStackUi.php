<?php

namespace TallStackUi;

use TallStackUi\View\Personalizations\Personalization;
use TallStackUi\View\Personalizations\Support\TailwindClassBuilder;

class TallStackUi
{
    public function directives(): TallStackUiDirectives
    {
        return new TallStackUiDirectives();
    }

    public function personalize(string $component = null): Personalization
    {
        return new Personalization($component);
    }

    public function tailwind(): TailwindClassBuilder
    {
        return new TailwindClassBuilder();
    }
}
