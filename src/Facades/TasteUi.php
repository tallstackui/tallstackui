<?php

namespace TasteUi\Facades;

use Illuminate\Support\Facades\Facade;
use TasteUi\TasteUiDirectives;

/**
 * @method static TasteUiDirectives directives()
 */
class TasteUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TasteUi\TasteUi::class;
    }
}
