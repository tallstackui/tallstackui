<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\Blade\ComponentPrefix;
use TallStackUi\Foundation\Support\Blade\Directives;
use TallStackUi\Foundation\Support\Blade\Wireable;

/**
 * @method static Wireable blade(?ComponentAttributeBag $attributes = null, bool $livewire = false)
 * @method static ComponentPrefix|string component(?string $name = null)
 * @method static string icon(string $key)
 * @method static Directives directives()
 * @method static Personalization personalize(?string $component = null, ?string $scope = null)
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
