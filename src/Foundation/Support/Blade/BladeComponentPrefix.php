<?php

namespace TallStackUi\Foundation\Support\Blade;

class BladeComponentPrefix
{
    public function __construct(private ?string $prefix = null)
    {
        $this->prefix = config('tallstackui.prefix');
    }

    public function __invoke(string $component): string
    {
        if (blank($this->prefix) || (bool) $this->prefix === false) {
            return $component;
        }

        return str($component)->start($this->prefix)->value();
    }
}
