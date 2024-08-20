<?php

namespace TallStackUi\Foundation\Components\Concerns\TallStackUiComponent;

use TallStackUi\Foundation\Components\Colors\ResolveColor;
use TallStackUi\Foundation\Components\Configurations\ResolveConfiguration;
use Throwable;

trait ManagesCompilation
{
    /** @throws Throwable */
    private function compile(array $data): array
    {
        // We use the "validate" method as a hook to
        // perform generic validations on components.
        if (method_exists($this, 'validate')) {
            $this->validate();
        }

        if ($colors = ResolveColor::of($this)) {
            $data = array_merge($data, ['colors' => [...$colors]]);
        }

        if ($configurations = ResolveConfiguration::of($this)) {
            $data = array_merge($data, ['configurations' => [...$configurations]]);
        }

        return [...$data];
    }
}
