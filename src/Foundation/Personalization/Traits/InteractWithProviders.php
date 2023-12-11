<?php

namespace TallStackUi\Foundation\Personalization\Traits;

use Exception;
use TallStackUi\Foundation\Colors\ResolveColor;
use TallStackUi\Foundation\Providers\ConfigurationProvider;

/**
 * @internal This trait is not meant to be used directly.
 */
trait InteractWithProviders
{
    /** @throws Exception */
    public function colors(): void
    {
        ResolveColor::resolve($this);
    }

    /** @throws Exception */
    public function configurations(): void
    {
        ConfigurationProvider::resolve($this);
    }
}
