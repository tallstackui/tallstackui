<?php

namespace TallStackUi\Foundation\Support;

class BladeComponentPrefix
{
    public function __construct(private ?string $prefix = null)
    {
        $this->prefix = config('tallstackui.prefix');
    }

    public function __invoke(string $component): string
    {
        if (blank($this->prefix)) {
            return $component;
        }

        return str($component)->start($this->prefix)->value();
    }
}
