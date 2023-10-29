<?php

namespace TallStackUi\View\Personalizations\Traits;

use Exception;
use TallStackUi\View\Personalizations\Providers\ColorProvider;
use TallStackUi\View\Personalizations\Providers\ConfigurationProvider;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithProviders
{
    /** @throws Exception */
    public function colors(): void
    {
        ColorProvider::resolve($this);
    }

    /** @throws Exception */
    public function configurations(): void
    {
        ConfigurationProvider::resolve($this);
    }
}
