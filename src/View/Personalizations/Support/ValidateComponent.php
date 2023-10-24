<?php

namespace TallStackUi\View\Personalizations\Support;

use Exception;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Select\Styled;
use TallStackUi\View\Personalizations\Support\Validations\DialogValidations;
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
            Toast::class => fn () => (new ToastValidations())(),
            Styled::class => fn () => (new SelectStyledValidations())($component),
            default => throw new Exception('No validation available for this component'),
        })();
    }
}
