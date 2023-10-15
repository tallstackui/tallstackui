<?php

namespace TallStackUi\View\Personalizations\Traits;

use TallStackUi\View\Personalizations\Providers\ColorProvider;
use TallStackUi\View\Personalizations\Providers\ConfigurationProvider;

trait InteractWithProviders
{
    public function colors(): void
    {
        ColorProvider::resolve($this);
    }

    public function configurations(): void
    {
        ConfigurationProvider::resolve($this);
    }
}
