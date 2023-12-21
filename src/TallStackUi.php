<?php

namespace TallStackUi;

use Illuminate\View\ComponentAttributeBag;
use TallStackUi\Foundation\Personalization\Personalization;
use TallStackUi\Foundation\Support\BladeDirectives;
use TallStackUi\Foundation\Support\BladeSupport;

class TallStackUi
{
    public function blade(?ComponentAttributeBag $attributes = null): BladeSupport
    {
        return app(BladeSupport::class, ['attributes' => $attributes]);
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
