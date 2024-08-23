<?php

namespace TallStackUi\Foundation\Support\Colors;

use Exception;
use Illuminate\View\Component;
use TallStackUi\Foundation\Support\Colors\Components\AlertColors;
use TallStackUi\Foundation\Support\Colors\Components\AvatarColors;
use TallStackUi\Foundation\Support\Colors\Components\BadgeColors;
use TallStackUi\Foundation\Support\Colors\Components\BannerColors;
use TallStackUi\Foundation\Support\Colors\Components\BooleanColors;
use TallStackUi\Foundation\Support\Colors\Components\ButtonColors;
use TallStackUi\Foundation\Support\Colors\Components\DialogColors;
use TallStackUi\Foundation\Support\Colors\Components\ErrorsColors;
use TallStackUi\Foundation\Support\Colors\Components\LinkColors;
use TallStackUi\Foundation\Support\Colors\Components\ProgressColors;
use TallStackUi\Foundation\Support\Colors\Components\RadioColors;
use TallStackUi\Foundation\Support\Colors\Components\RangeColors;
use TallStackUi\Foundation\Support\Colors\Components\RatingColors;
use TallStackUi\Foundation\Support\Colors\Components\StatsColors;
use TallStackUi\Foundation\Support\Colors\Components\ToastColors;
use TallStackUi\Foundation\Support\Colors\Components\ToggleColors;
use TallStackUi\Foundation\Support\Colors\Components\TooltipColors;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;
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
 * @internal
 */
class CompileColors
{
    /** @throws Exception */
    public static function of(Component $component): ?array
    {
        $reflect = app(ReflectComponent::class, ['component' => $component::class]);
        $parent = $reflect->parent()->name;

        // This way of using match was designed for deep personalization,
        // for customized components that extend the original components.
        $class = match (true) {
            $parent === Alert::class => AlertColors::class,
            $parent === Avatar::class => AvatarColors::class,
            $parent === Badge::class => BadgeColors::class,
            $parent === Banner::class => BannerColors::class,
            $parent === Boolean::class => BooleanColors::class,
            $parent === Dialog::class => DialogColors::class,
            $parent === Errors::class => ErrorsColors::class,
            $parent === Link::class => LinkColors::class,
            $parent === Toast::class => ToastColors::class,
            $parent === Toggle::class => ToggleColors::class,
            $parent === Tooltip::class => TooltipColors::class,
            $parent === Stats::class => StatsColors::class,
            $parent === Range::class => RangeColors::class,
            $parent === Rating::class => RatingColors::class,
            $parent === Button::class || $parent === Circle::class => ButtonColors::class,
            $parent === Progress::class || $parent === ProgressCircle::class => ProgressColors::class,
            $parent === Radio::class || $parent === Checkbox::class => RadioColors::class,
            default => null,
        };

        if (! $class) {
            return null;
        }

        return app($class, ['component' => $component, 'reflect' => $reflect])();
    }
}
