<?php

namespace TallStackUi\Foundation\Providers;

use Exception;
use TallStackUi\Foundation\Personalization\Support\Colors\AlertColors;
use TallStackUi\Foundation\Personalization\Support\Colors\AvatarColors;
use TallStackUi\Foundation\Personalization\Support\Colors\BadgeColors;
use TallStackUi\Foundation\Personalization\Support\Colors\BannerColors;
use TallStackUi\Foundation\Personalization\Support\Colors\ButtonColors;
use TallStackUi\Foundation\Personalization\Support\Colors\ErrorsColors;
use TallStackUi\Foundation\Personalization\Support\Colors\RadioColors;
use TallStackUi\Foundation\Personalization\Support\Colors\RangeColors;
use TallStackUi\Foundation\Personalization\Support\Colors\ToggleColors;
use TallStackUi\Foundation\Personalization\Support\Colors\TooltipColors;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Banner;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Range;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Tooltip;

/**
 * @internal This class is not meant to be used directly.
 */
class ColorProvider
{
    /** @throws Exception */
    public static function resolve(object $component): array
    {
        $name = get_class($component);

        // This way of using match was designed for deep personalization,
        // for customized components that extend the original components.
        $class = match (true) {
            $component instanceof Alert => AlertColors::class,
            $component instanceof Avatar => AvatarColors::class,
            $component instanceof Badge => BadgeColors::class,
            $component instanceof Banner => BannerColors::class,
            $component instanceof Errors => ErrorsColors::class,
            $component instanceof Toggle => ToggleColors::class,
            $component instanceof Tooltip => TooltipColors::class,
            $component instanceof Range => RangeColors::class,
            $component instanceof Button || $component instanceof Circle => ButtonColors::class,
            $component instanceof Radio || $component instanceof Checkbox => RadioColors::class,
            default => throw new Exception("No colors available for the component: [$name]"),
        };

        return [...(new $class($component))()];
    }
}
