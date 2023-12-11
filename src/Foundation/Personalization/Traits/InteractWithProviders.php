<?php

namespace TallStackUi\Foundation\Personalization\Traits;

use Exception;
use TallStackUi\Foundation\Providers\ColorProvider;
use TallStackUi\Foundation\Providers\ConfigurationProvider;

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
