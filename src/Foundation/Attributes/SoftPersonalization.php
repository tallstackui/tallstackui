<?php

namespace TallStackUi\Foundation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SoftPersonalization
{
    public function __construct(protected string $key)
    {
        //
    }

    public function key(bool $prefix = true): string
    {
        return $prefix ? 'tallstack-ui::personalizations.'.$this->key : $this->key;
    }
}
