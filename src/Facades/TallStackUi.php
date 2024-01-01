<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\BladeComponentResolver;
use TallStackUi\Foundation\Support\BladeDirectives;
use TallStackUi\Foundation\Support\BladeSupport;

/**
 * @method static BladeSupport blade(?ComponentAttributeBag $attributes = null, bool $livewire = false)
 * @method static BladeComponentResolver|string component(?string $name = null)
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
