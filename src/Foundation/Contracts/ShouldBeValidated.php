<?php

namespace TallStackUi\Foundation\Contracts;

use Throwable;

interface ShouldBeValidated
{
    /** @throws Throwable */
    public function validate(): void;
}
