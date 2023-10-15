<?php

namespace TallStackUi\View\Personalizations\Traits;

use TallStackUi\View\Personalizations\Providers\ColorServiceProvider;

trait InteractWithProviders
{
    public function colors(): void
    {
        ColorServiceProvider::from($this);
    }
}
