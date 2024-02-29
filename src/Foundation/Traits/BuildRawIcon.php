<?php

namespace TallStackUi\Foundation\Traits;

use Exception;
use TallStackUi\Foundation\Exceptions\InappropriateIconGuideExecution;
use TallStackUi\Foundation\Support\Components\IconGuide;

trait BuildRawIcon
{
    /** @throws Exception */
    public function icon(?string $path = null): string
    {
        InappropriateIconGuideExecution::validate(static::class);

        $result = IconGuide::build($this);

        if ($path) {
            return $path.$result;
        }

        return $result;
    }
}
