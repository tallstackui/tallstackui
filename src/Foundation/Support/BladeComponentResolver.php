<?php

namespace TallStackUi\Foundation\Support;

class BladeComponentResolver
{
    public function __construct(private ?string $prefix = null)
    {
        $this->prefix = config('tallstackui.prefix');
    }

    public function deprefixing(string $name): string
    {
        return $this->handle($name, false);
    }

    public function prefixing(string $component): string
    {
        return $this->handle($component);
    }

    public function resolve(string $name): string
    {
        return $this->prefixing($name);
    }

    private function handle(string $name, bool $prefixing = true): string
    {
        if (blank($this->prefix)) {
            return $name;
        }

        $string = str($name);

        if ($prefixing) {
            return $string->start($this->prefix)->value();
        }

        return $string->after($this->prefix)->value();
    }
}
