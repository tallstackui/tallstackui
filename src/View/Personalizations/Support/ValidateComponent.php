<?php

namespace TallStackUi\View\Personalizations\Support;

use Exception;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Icon;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Select\Styled;
use TallStackUi\View\Personalizations\Support\Validations\DialogValidations;
use TallStackUi\View\Personalizations\Support\Validations\ErrorsValidations;
use TallStackUi\View\Personalizations\Support\Validations\IconValidations;
use TallStackUi\View\Personalizations\Support\Validations\ModalValidations;
use TallStackUi\View\Personalizations\Support\Validations\SelectStyledValidations;
use TallStackUi\View\Personalizations\Support\Validations\ToastValidations;

/**
 * @internal This class is not meant to be used directly.
 */
class ValidateComponent
{
    /** @throws Exception */
    public static function validate(object $component): void
    {
        $class = match (get_class($component)) {
            Dialog::class => DialogValidations::class,
            Errors::class => ErrorsValidations::class,
            Toast::class => ToastValidations::class,
            Styled::class => SelectStyledValidations::class,
            Icon::class => IconValidations::class,
            Modal::class => ModalValidations::class,
            default => throw new Exception("No validation available for the component: [$component]"),
        };

        (new $class())($component);
    }
}
