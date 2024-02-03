<?php

namespace TallStackUi;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\Blade\BladeComponentPrefix;
use TallStackUi\Foundation\Support\Blade\BladeDirectives;
use TallStackUi\Foundation\Support\Blade\BladeSupport;
use TallStackUi\Foundation\Support\Components\IconGuide;

class TallStackUi
{
    public function blade(?ComponentAttributeBag $attributes = null, bool $livewire = false): BladeSupport
    {
        return app(BladeSupport::class, [
            'attributes' => $attributes,
            'livewire' => $livewire,
        ]);
    }

    public function component(?string $name = null): BladeComponentPrefix|string
    {
        $prefix = app(BladeComponentPrefix::class);

        return blank($name) ? $prefix : $prefix($name);
    }

    public function directives(): BladeDirectives
    {
        return app(BladeDirectives::class);
    }

    public function icon(string $key): string
    {
        return app(IconGuide::class)::internal($key);
    }

    public function personalize(?string $component = null): Personalization
    {
        return app(Personalization::class, ['component' => $component]);
    }
}
