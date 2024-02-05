<?php

namespace TallStackUi\Foundation\Exceptions;

use Exception;
use TallStackUi\View\Components\Icon;
use TallStackUi\View\Components\Tooltip;

class InappropriateIconGuideExecution extends Exception
{
    public function __construct()
    {
        parent::__construct('The IconGuide should only be called through Icon and Tooltip components.');
    }

    /** @throws InappropriateIconGuideExecution */
    public static function validate(string $class): ?self
    {
        if (in_array($class, [Icon::class, Tooltip::class])) {
            return null;
        }

        throw new self();
    }
}
