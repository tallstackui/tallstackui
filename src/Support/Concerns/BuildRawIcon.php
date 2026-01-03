<?php

namespace TallStackUi\Support\Concerns;

use Exception;
use TallStackUi\Exceptions\InappropriateIconGuideExecution;
use TallStackUi\Support\Icons\IconGuideMap;

trait BuildRawIcon
{
    /** @throws Exception */
    public function raw(?string $path = null): string
    {
        InappropriateIconGuideExecution::validate(static::class);

        return IconGuideMap::build($this, $path);
    }
}
