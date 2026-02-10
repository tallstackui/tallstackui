<?php

namespace TallStackUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \TallStackUi\Support\Breadcrumbs\BreadcrumbRegistry breadcrumbs()
 * @method static \TallStackUi\Support\Blade\Wireable blade(?\Illuminate\View\ComponentAttributeBag $attributes = null, bool $livewire = false)
 * @method static \TallStackUi\Support\Blade\Directives directives()
 * @method static string icon(string $key)
 * @method static \TallStackUi\Customization\Customization customize(?string $component = null, ?string $scope = null)
 * @method static \TallStackUi\Support\Blade\ComponentPrefix|string prefix(?string $name = null)
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
