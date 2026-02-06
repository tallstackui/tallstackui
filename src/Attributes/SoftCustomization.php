<?php

namespace TallStackUi\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SoftCustomization
{
    public function __construct(public string $key)
    {
        //
    }

    public function prefixed(): string
    {
        return 'ts-ui::customization.'.$this->key;
    }
}
