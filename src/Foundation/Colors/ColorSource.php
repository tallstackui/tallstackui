<?php

namespace TallStackUi\Foundation\Colors;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ColorSource
{
    public function __construct(protected string $component)
    {
        //
    }

    public function compile(): string
    {
        return $this->component;
    }
}
