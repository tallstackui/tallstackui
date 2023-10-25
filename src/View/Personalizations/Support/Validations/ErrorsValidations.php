<?php

namespace TallStackUi\View\Personalizations\Support\Validations;

use Exception;
use TallStackUi\View\Components\Errors;
use Throwable;

class ErrorsValidations
{
    /** @throws Throwable */
    public function __invoke(Errors $errors): void
    {
        throw_if(blank($errors->title), new Exception('The [title] cannot be empty'));
    }
}
