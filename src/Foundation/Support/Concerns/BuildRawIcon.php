<?php

namespace TallStackUi\Foundation\Support\Concerns;

use Exception;
use TallStackUi\Foundation\Exceptions\InappropriateIconGuideExecution;
use TallStackUi\Foundation\Support\Icons\IconGuideMap;

trait BuildRawIcon
{
    /** @throws Exception */
    public function raw(?string $path = null): string
    {
        InappropriateIconGuideExecution::validate(static::class);

        return IconGuideMap::build($this, $path);
    }
}
