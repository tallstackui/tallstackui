<?php

namespace TasteUi\Facades;

use Illuminate\Support\Facades\Facade;
use TasteUi\Support\Elements\Color;
use TasteUi\TasteUiDirectives;

/**
 * @method static Color colors()
 * @method static TasteUiDirectives directives()
 */
class TasteUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TasteUi\TasteUi::class;
    }
}
