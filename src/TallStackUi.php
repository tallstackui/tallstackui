<?php

namespace TallStackUi;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\BladeComponentResolver;
use TallStackUi\Foundation\Support\BladeDirectives;
use TallStackUi\Foundation\Support\BladeSupport;

class TallStackUi
{
    public function blade(?ComponentAttributeBag $attributes = null, bool $livewire = false): BladeSupport
    {
        return app(BladeSupport::class, [
            'attributes' => $attributes,
            'livewire' => $livewire,
        ]);
    }

    public function component(): BladeComponentResolver
    {
        return app(BladeComponentResolver::class);
    }

    public function directives(): BladeDirectives
    {
        return app(BladeDirectives::class);
    }

    public function personalize(?string $component = null): Personalization
    {
        return app(Personalization::class, ['component' => $component]);
    }
}
