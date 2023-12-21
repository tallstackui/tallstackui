<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\BladeDirectives;
use TallStackUi\Foundation\Support\BladeSupport;

/**
 * @method static Personalization personalize(?string $component = null)
 * @method static BladeDirectives directives()
 * @method static BladeSupport blade(?ComponentAttributeBag $attributes = null)
 */
class TallStackUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TallStackUi\TallStackUi::class;
    }
}
