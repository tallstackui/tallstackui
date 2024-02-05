<?php

namespace TallStackUi\Foundation\Traits;

use Exception;
use TallStackUi\Foundation\Exceptions\InappropriateIconGuideExecution;
use TallStackUi\Foundation\Support\Components\IconGuide;

trait BuildRawIcon
{
    /** @throws Exception */
    public function icon(): string
    {
        InappropriateIconGuideExecution::validate(static::class);

        return IconGuide::build($this);
    }
}
