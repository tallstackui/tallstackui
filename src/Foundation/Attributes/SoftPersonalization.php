<?php

namespace TallStackUi\Foundation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SoftPersonalization
{
    public function __construct(public string $key)
    {
        //
    }

    public function prefixed(): string
    {
        return 'tallstack-ui::personalizations.'.$this->key;
    }
}
