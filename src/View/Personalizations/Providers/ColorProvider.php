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
        $data = (match (get_class($component)) {
            Alert::class => fn () => (new AlertColors())($component),
            Avatar::class => fn () => (new AvatarColors())($component),
            Badge::class => fn () => (new BadgeColors())($component),
            Button::class, Circle::class => fn () => (new ButtonColors())($component),
            Errors::class => fn () => (new ErrorsColors())($component),
            Radio::class, Checkbox::class => fn () => (new RadioColors())($component),
            Toggle::class => fn () => (new ToggleColors())($component),
            Tooltip::class => fn () => (new TooltipColors())($component),
            default => throw new Exception("No colors available for the component: [$component]"),
        })();

        FacadeView::composer($component->render()->name(), fn (View $view) => $view->with('colors', [...$data]));
    }
}
