<?php

namespace TallStackUi\View\Personalizations\Providers;

use Exception;
use Illuminate\Support\Facades\View as FacadeView;
use Illuminate\View\View;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Tooltip;
use TallStackUi\View\Personalizations\Support\Colors\AlertColors;
use TallStackUi\View\Personalizations\Support\Colors\AvatarColors;
use TallStackUi\View\Personalizations\Support\Colors\BadgeColors;
use TallStackUi\View\Personalizations\Support\Colors\ButtonColors;
use TallStackUi\View\Personalizations\Support\Colors\ErrorsColors;
use TallStackUi\View\Personalizations\Support\Colors\RadioColors;
use TallStackUi\View\Personalizations\Support\Colors\ToggleColors;
use TallStackUi\View\Personalizations\Support\Colors\TooltipColors;

/**
 * @internal This class is not meant to be used directly.
 */
class ColorProvider
{
    /** @throws Exception */
    public static function resolve(object $component): void
    {
        // This way of using match was designed for deep personalization,
        // for customized components that extend the original components.
        $class = match (true) {
            $component instanceof Alert => AlertColors::class,
            $component instanceof Avatar => AvatarColors::class,
            $component instanceof Badge => BadgeColors::class,
            $component instanceof Errors => ErrorsColors::class,
            $component instanceof Toggle => ToggleColors::class,
            $component instanceof Tooltip => TooltipColors::class,
            $component instanceof Button || $component instanceof Circle => ButtonColors::class,
            $component instanceof Radio || $component instanceof Checkbox => RadioColors::class,
            default => throw new Exception("No colors available for the component: [$component]"),
        };

        FacadeView::composer($component->render()->name(), fn (View $view) => $view->with('colors', [...(new $class($component))()]));
    }
}
