<?php

namespace TallStackUi;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\Blade\BladeComponentPrefix;
use TallStackUi\Foundation\Support\Blade\BladeDirectives;
use TallStackUi\Foundation\Support\Blade\BladeSupport;
use TallStackUi\Foundation\Support\Icons\IconGuideMap;

class TallStackUi
{
    /**
     * Create an instance of the BladeSupport class.
     */
    public function blade(?ComponentAttributeBag $attributes = null, bool $livewire = false): BladeSupport
    {
        return app(BladeSupport::class, [
            'attributes' => $attributes,
            'livewire' => $livewire,
        ]);
    }

    /**
     * Get the component name adding the prefix when set.
     */
    public function component(?string $name = null): BladeComponentPrefix|string
    {
        $prefix = app(BladeComponentPrefix::class);

        return blank($name) ? $prefix : $prefix->add($name);
    }

    /**
     * Create an instance of the BladeDirectives class.
     */
    public function directives(): BladeDirectives
    {
        return app(BladeDirectives::class);
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
}
