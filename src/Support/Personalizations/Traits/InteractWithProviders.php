<?php

namespace TallStackUi\Support\Personalizations\Traits;

use Exception;
use TallStackUi\Providers\ColorProvider;
use TallStackUi\Providers\ConfigurationProvider;

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
