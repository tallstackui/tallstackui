<?php

namespace TasteUi\Facades;

use Illuminate\Support\Facades\Facade;

class TasteUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TasteUi\TasteUi::class;
    }
}
