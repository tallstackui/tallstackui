<?php

namespace TallStackUi;

use TallStackUi\Foundation\Personalization\Personalization;

class TallStackUi
{
    public function directives(): TallStackUiDirectives
    {
        return app(TallStackUiDirectives::class);
    }

    public function personalize(?string $component = null): Personalization
    {
        return app(Personalization::class, ['component' => $component]);
    }
}
