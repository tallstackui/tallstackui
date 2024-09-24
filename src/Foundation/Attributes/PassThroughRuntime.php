<?php

namespace TallStackUi\Foundation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class PassThroughRuntime
{
    public function __construct(public string $runtime)
    {
        //
    }
}
