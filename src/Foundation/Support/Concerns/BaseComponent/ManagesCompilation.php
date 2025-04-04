<?php

namespace TallStackUi\Foundation\Support\Concerns\BaseComponent;

use TallStackUi\Foundation\Support\Colors\CompileColors;
use TallStackUi\Foundation\Support\Configurations\CompileConfigurations;
use Throwable;

trait ManagesCompilation
{
    /** @throws Throwable */
    private function compile(array $data): array
    {
        // We use the "validate" method as a hook to
        // perform generic validations on components.
        if (method_exists($this, 'validate')) {
            $this->validate($data);
        }

        if ($colors = CompileColors::of($this)) {
            $data = array_merge($data, ['colors' => [...$colors]]);
        }

        if ($configurations = CompileConfigurations::of($this)) {
            $data = array_merge($data, ['configurations' => [...$configurations]]);
        }

        return [...$data];
    }
}
