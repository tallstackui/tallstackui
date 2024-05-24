<?php

namespace TallStackUi\Foundation\Colors;

use Exception;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Banner;
use TallStackUi\View\Components\Boolean;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Range;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Link;
use TallStackUi\View\Components\Progress\Circle as ProgressCircle;
use TallStackUi\View\Components\Progress\Progress;
use TallStackUi\View\Components\Rating;
use TallStackUi\View\Components\Stats;
use TallStackUi\View\Components\Tooltip;

/**
 * @internal This class is not meant to be used directly.
 */
class ResolveColor
{
    /** @throws Exception */
    public static function from(object $component): ?array
    {
        // This way of using match was designed for deep personalization,
        // for customized components that extend the original components.
        $class = match (true) {
            $component instanceof Alert => AlertColors::class,
            $component instanceof Avatar => AvatarColors::class,
            $component instanceof Badge => BadgeColors::class,
            $component instanceof Banner => BannerColors::class,
            $component instanceof Boolean => BooleanColors::class,
            $component instanceof Dialog => DialogColors::class,
            $component instanceof Errors => ErrorsColors::class,
            $component instanceof Link => LinkColors::class,
            $component instanceof Toast => ToastColors::class,
            $component instanceof Toggle => ToggleColors::class,
            $component instanceof Tooltip => TooltipColors::class,
            $component instanceof Stats => StatsColors::class,
            $component instanceof Range => RangeColors::class,
            $component instanceof Rating => RatingColors::class,
            $component instanceof Button || $component instanceof Circle => ButtonColors::class,
            $component instanceof Progress || $component instanceof ProgressCircle => ProgressColors::class,
            $component instanceof Radio || $component instanceof Checkbox => RadioColors::class,
            default => null,
        };

        if (! $class) {
            return null;
        }

        return app($class, ['component' => $component])();
    }
}
