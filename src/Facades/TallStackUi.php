<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;
use TallStackUi\TallStackUiDirectives;
use TallStackUi\View\Personalizations\Personalization;
use TallStackUi\View\Personalizations\Support\Color;

/**
 * @method static Personalization personalize(?string $component = null)
 * @method static Color colors()
 * @method static TallStackUiDirectives directives()
 */
class TallStackUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TallStackUi\TallStackUi::class;
    }
}
