<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\Blade\BladeComponentPrefix;
use TallStackUi\Foundation\Support\Blade\BladeDirectives;
use TallStackUi\Foundation\Support\Blade\BladeSupport;

/**
 * @method static BladeSupport blade(?ComponentAttributeBag $attributes = null, bool $livewire = false)
 * @method static BladeComponentPrefix|string component(?string $name = null)
 * @method static string icon(string $key)
 * @method static BladeDirectives directives()
 * @method static Personalization personalize(?string $component = null)
 *
 * @see \TallStackUi\TallStackUi
 */
class TallStackUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TallStackUi\TallStackUi::class;
    }
}
