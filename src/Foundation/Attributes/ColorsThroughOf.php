<?php

namespace TallStackUi\Foundation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ColorsThroughOf
{
    public function __construct(public string $class)
    {
        //
    }
}
