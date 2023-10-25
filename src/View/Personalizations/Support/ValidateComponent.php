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
        (match (get_class($component)) {
            Dialog::class => fn () => (new DialogValidations())(),
            Errors::class => fn () => (new ErrorsValidations())($component),
            Toast::class => fn () => (new ToastValidations())(),
            Styled::class => fn () => (new SelectStyledValidations())($component),
            Icon::class => fn () => (new IconValidations())($component),
            Modal::class => fn () => (new ModalValidations())($component),
            default => throw new Exception("No validation available for the component: [$component]"),
        })();
    }
}
