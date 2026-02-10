<?php

namespace TallStackUi;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Customization\Customization;
use TallStackUi\Support\Blade\ComponentPrefix;
use TallStackUi\Support\Blade\Directives;
use TallStackUi\Support\Blade\Wireable;
use TallStackUi\Support\Breadcrumbs\BreadcrumbRegistry;
use TallStackUi\Support\Icons\IconGuideMap;

class TallStackUi
{
    /**
     * Create an instance of the BladeSupport class.
     */
    public function blade(?ComponentAttributeBag $attributes = null, bool $livewire = false): Wireable
    {
        return app(Wireable::class, [
            'attributes' => $attributes,
            'livewire' => $livewire,
        ]);
    }

    /**
     * Get the breadcrumb registry for defining route-based breadcrumbs.
     */
    public function breadcrumbs(): BreadcrumbRegistry
    {
        return app(BreadcrumbRegistry::class);
    }

    /**
     * Create an instance of the Customization class.
     */
    public function customize(?string $component = null, ?string $scope = null): Customization
    {
        return app(Customization::class, ['component' => $component, 'scope' => $scope]);
    }

    /**
     * Create an instance of the BladeDirectives class.
     */
    public function directives(): Directives
    {
        return app(Directives::class);
    }

    /**
     * Get the internal icon path.
     */
    public function icon(string $key): string
    {
        return app(IconGuideMap::class)::internal($key);
    }

    /**
     * Set the component prefix or get the ComponentPrefix instance when $name is null.
     */
    public function prefix(?string $name = null): ComponentPrefix|string
    {
        $prefix = app(ComponentPrefix::class);

        return blank($name) ? $prefix : $prefix->add($name);
    }
}
