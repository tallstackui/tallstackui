<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \TallStackUi\Foundation\Support\Blade\Wireable blade(?\Illuminate\View\ComponentAttributeBag $attributes = null, bool $livewire = false)
 * @method static \TallStackUi\Foundation\Support\Blade\Directives directives()
 * @method static string icon(string $key)
 * @method static \TallStackUi\Foundation\Personalization\Personalization personalize(?string $component = null, ?string $scope = null)
 * @method static \TallStackUi\Foundation\Support\Blade\ComponentPrefix|string prefix(?string $name = null)
 *
 * @see \TallStackUi\TallStackUi
 */
class TallStackUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'TallStackUi';
    }
}
