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
    public function __construct(
        private readonly object $component
    ) {
        //
    }

    /** @throws Exception */
    public static function resolve(object $component): void
    {
        $method = match (get_class($component)) {
            Alert::class => 'alert',
            Avatar::class => 'avatar',
            Badge::class => 'badge',
            Button::class, Circle::class => 'button',
            Errors::class => 'errors',
            Radio::class, Checkbox::class => 'radio',
            Toggle::class => 'toggle',
            Tooltip::class => 'tooltip',
            default => throw new Exception('No colors available for this component'),
        };

        $class = new self($component);

        FacadeView::composer($component->render()->name(), fn (View $view) => $view->with('colors', [...$class->$method($component)]));
    }

    private function alert(): array
    {
        /** @var Alert $component */
        $component = $this->component;

        return (new AlertColors())($component);
    }

    private function avatar(): array
    {
        /** @var Avatar $component */
        $component = $this->component;

        return (new AvatarColors())($component);
    }

    private function badge(): array
    {
        /** @var Badge $component */
        $component = $this->component;

        return (new BadgeColors())($component);
    }

    private function button(): array
    {
        /** @var Button|Circle $component */
        $component = $this->component;

        return (new ButtonColors())($component);
    }

    private function errors(): array
    {
        /** @var Errors $component */
        $component = $this->component;

        return (new ErrorsColors())($component);
    }

    private function radio(): array
    {
        /** @var Radio|Checkbox $component */
        $component = $this->component;

        return (new RadioColors())($component);
    }

    private function toggle(): array
    {
        /** @var Toggle $component */
        $component = $this->component;

        return (new ToggleColors())($component);
    }

    private function tooltip(): array
    {
        /** @var Tooltip $component */
        $component = $this->component;

        return (new TooltipColors())($component);
    }
}
