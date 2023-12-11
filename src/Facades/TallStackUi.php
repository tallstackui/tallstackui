<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;
use TallStackUi\Support\Personalizations\Personalization;
use TallStackUi\TallStackUiDirectives;

/**
 * @method static Personalization personalize(?string $component = null)
 * @method static TallStackUiDirectives directives()
 */
class TallStackUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TallStackUi\TallStackUi::class;
    }
}
