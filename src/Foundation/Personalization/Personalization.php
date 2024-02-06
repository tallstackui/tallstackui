<?php

namespace TallStackUi\Foundation\Personalization;

use Closure;
use Exception;
use RuntimeException;
use TallStackUi\Contracts\Personalizable;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Banner;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Card;
use TallStackUi\View\Components\Clipboard;
use TallStackUi\View\Components\Dropdown\Dropdown;
use TallStackUi\View\Components\Dropdown\Items as DropdownItems;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Color;
use TallStackUi\View\Components\Form\Error;
use TallStackUi\View\Components\Form\Hint;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Label;
use TallStackUi\View\Components\Form\Number;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Pin;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Range;
use TallStackUi\View\Components\Form\Tag;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\TimePicker;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Form\Upload;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Link;
use TallStackUi\View\Components\Loading;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Reaction;
use TallStackUi\View\Components\Select\Native as SelectNative;
use TallStackUi\View\Components\Select\Styled as SelectStyled;
use TallStackUi\View\Components\Slide;
use TallStackUi\View\Components\SwitchTheme;
use TallStackUi\View\Components\Tab\Items as TabItems;
use TallStackUi\View\Components\Tab\Tab;
use TallStackUi\View\Components\Table;
use TallStackUi\View\Components\Tooltip;
use TallStackUi\View\Components\Wrapper\Input as InputWrapper;
use TallStackUi\View\Components\Wrapper\Radio as RadioWrapper;

/**
 * @internal This class is not meant to be used directly.
 */
class Personalization
{
    public function __construct(public ?string $component = null)
    {
        //
    }

    public function alert(): PersonalizationResources
    {
        return app($this->component(Alert::class));
    }

    public function avatar(): PersonalizationResources
    {
        return app($this->component(Avatar::class));
    }

    public function badge(): PersonalizationResources
    {
        return app($this->component(Badge::class));
    }

    public function banner(): PersonalizationResources
    {
        return app($this->component(Banner::class));
    }

    public function block(string|array $name, string|Closure|Personalizable|null $code = null): PersonalizationResources
    {
        return $this->instance()->block($name, $code);
    }

    public function button(?string $component = null): PersonalizationResources
    {
        $component ??= 'button';

        $class = match ($component) {
            'button' => Button::class,
            'circle' => Circle::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function card(): PersonalizationResources
    {
        return app($this->component(Card::class));
    }

    public function clipboard(): PersonalizationResources
    {
        return app($this->component(Clipboard::class));
    }

    public function dialog(): PersonalizationResources
    {
        return app($this->component(Dialog::class));
    }

    public function dropdown(?string $component = null): PersonalizationResources
    {
        $component ??= 'dropdown';

        $class = match ($component) {
            'dropdown' => Dropdown::class,
            'items' => DropdownItems::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function errors(): PersonalizationResources
    {
        return app($this->component(Errors::class));
    }

    public function form(?string $component = null): PersonalizationResources
    {
        $component ??= 'input';

        $class = match ($component) {
            'checkbox' => Checkbox::class,
            'color' => Color::class,
            'error' => Error::class,
            'upload' => Upload::class,
            'hint' => Hint::class,
            'input' => Input::class,
            'label' => Label::class,
            'number' => Number::class,
            'range' => Range::class,
            'password' => Password::class,
            'pin' => Pin::class,
            'radio' => Radio::class,
            'tag' => Tag::class,
            'textarea' => Textarea::class,
            'timepicker' => TimePicker::class,
            'toggle' => Toggle::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function instance(): PersonalizationResources
    {
        if (! $this->component) {
            throw new RuntimeException('No component has been set');
        }

        if (str_contains($this->component, 'tallstack-ui::personalizations')) {
            $this->component = str_replace('tallstack-ui::personalizations.', '', $this->component);
        }

        $parts = explode('.', $this->component);
        $main = $parts[0];
        $secondary = $parts[1] ?? null;

        if (! method_exists($this, $main)) {
            throw new RuntimeException("The method [{$main}] is not supported");
        }

        return call_user_func([$this, $main], $main === $secondary ?: $secondary);
    }

    public function link(): PersonalizationResources
    {
        return app($this->component(Link::class));
    }

    public function loading(): PersonalizationResources
    {
        return app($this->component(Loading::class));
    }

    public function modal(): PersonalizationResources
    {
        return app($this->component(Modal::class));
    }

    public function reaction(): PersonalizationResources
    {
        return app($this->component(Reaction::class));
    }

    public function select(?string $component = null): PersonalizationResources
    {
        $component ??= 'native';

        $class = match ($component) {
            'native' => SelectNative::class,
            'styled' => SelectStyled::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function slide(): PersonalizationResources
    {
        return app($this->component(Slide::class));
    }

    public function tab(?string $component = null): PersonalizationResources
    {
        $component ??= 'tabs';

        $class = match ($component) {
            'tabs' => Tab::class,
            'items' => TabItems::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function table(): PersonalizationResources
    {
        return app($this->component(Table::class));
    }

    public function toast(): PersonalizationResources
    {
        return app($this->component(Toast::class));
    }

    public function tooltip(): PersonalizationResources
    {
        return app($this->component(Tooltip::class));
    }

    public function switchTheme(): PersonalizationResources
    {
        return app($this->component(SwitchTheme::class));
    }

    public function wrapper(?string $component = null): PersonalizationResources
    {
        $component ??= 'input';

        $class = match ($component) {
            'input' => InputWrapper::class,
            'radio' => RadioWrapper::class,
            default => $component,
        };

        return app($this->component($class));
    }

    /** @throws Exception */
    private function component(string $class): string
    {
        $component = array_search($class, tallstackui_components_soft_personalized());

        if (! $component) {
            throw new Exception("Component [{$class}] is not allowed to be personalized");
        }

        return $component;
    }
}
