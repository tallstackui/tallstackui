<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;
use TallStackUi\Support\Elements\Color;
use TallStackUi\Support\Personalization;
use TallStackUi\TallStackUiDirectives;

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
