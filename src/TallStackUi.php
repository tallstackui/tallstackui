<?php

namespace TallStackUi;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\Blade\ComponentPrefix;
use TallStackUi\Foundation\Support\Blade\Directives;
use TallStackUi\Foundation\Support\Blade\Wireable;
use TallStackUi\Foundation\Support\Icons\IconGuideMap;

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
     * Create an instance of the Personalization class.
     */
    public function personalize(?string $component = null, ?string $scope = null): Personalization
    {
        return app(Personalization::class, ['component' => $component, 'scope' => $scope]);
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
