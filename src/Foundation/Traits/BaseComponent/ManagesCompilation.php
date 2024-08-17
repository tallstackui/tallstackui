<?php

namespace TallStackUi\Foundation\Traits\BaseComponent;

use TallStackUi\Foundation\Colors\ResolveColor;
use TallStackUi\Foundation\ResolveConfiguration;
use Throwable;

trait ManagesCompilation
{
    /** @throws Throwable */
    private function compile(array $data): array
    {
        if (method_exists($this, 'validate')) {
            $this->validate();
        }

        if ($colors = ResolveColor::from($this)) {
            $data = array_merge($data, ['colors' => [...$colors]]);
        }

        if ($configurations = ResolveConfiguration::from($this)) {
            $data = array_merge($data, ['configurations' => [...$configurations]]);
        }

        return [...$data];
    }
}
