<?php

namespace TallStackUi\Foundation\Support;

class BladeComponentResolver
{
    public function __construct(private ?string $prefix = null)
    {
        $this->prefix = config('tallstackui.prefix');
    }

    public function prefix(string $component): string
    {
        if (blank($this->prefix)) {
            return $component;
        }

        return str($component)->start($this->prefix)->value();
    }

    public function resolver(string $name): string
    {
        return $this->prefix($name);
    }
}
