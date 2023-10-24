<?php

namespace TallStackUi\View\Personalizations;

use Closure;
use InvalidArgumentException;
use TallStackUi\Contracts\Personalizable as PersonalizableClass;
use TallStackUi\View\Personalizations\Components\Alert;
use TallStackUi\View\Personalizations\Components\Avatar;
use TallStackUi\View\Personalizations\Components\Badge;
use TallStackUi\View\Personalizations\Components\Button\Button;
use TallStackUi\View\Personalizations\Components\Button\Circle;
use TallStackUi\View\Personalizations\Components\Card;
use TallStackUi\View\Personalizations\Components\Dropdown\Dropdown;
use TallStackUi\View\Personalizations\Components\Dropdown\Items as DropdownItems;
use TallStackUi\View\Personalizations\Components\Error;
use TallStackUi\View\Personalizations\Components\Errors;
use TallStackUi\View\Personalizations\Components\Form\Checkbox;
use TallStackUi\View\Personalizations\Components\Form\Input;
use TallStackUi\View\Personalizations\Components\Form\Label;
use TallStackUi\View\Personalizations\Components\Form\Password;
use TallStackUi\View\Personalizations\Components\Form\Radio;
use TallStackUi\View\Personalizations\Components\Form\Textarea;
use TallStackUi\View\Personalizations\Components\Form\Toggle;
use TallStackUi\View\Personalizations\Components\Hint;
use TallStackUi\View\Personalizations\Components\Interactions\Dialog;
use TallStackUi\View\Personalizations\Components\Interactions\Toast;
use TallStackUi\View\Personalizations\Components\Modal;
use TallStackUi\View\Personalizations\Components\Select\Native as SelectNative;
use TallStackUi\View\Personalizations\Components\Select\Styled as SelectStyled;
use TallStackUi\View\Personalizations\Components\Tabs\Items as TabItems;
use TallStackUi\View\Personalizations\Components\Tabs\Tab;
use TallStackUi\View\Personalizations\Components\Tooltip;
use TallStackUi\View\Personalizations\Components\Wrapper\Input as InputWrapper;
use TallStackUi\View\Personalizations\Components\Wrapper\Radio as RadioWrapper;
use TallStackUi\View\Personalizations\Components\Wrapper\Select as SelectWrapper;
use TallStackUi\View\Personalizations\Contracts\Personalizable as PersonalizableContract;

/**
 * @internal This class is not meant to be used directly.
 */
class Personalization
{
    public const PERSONALIZABLES = [
        'tallstack-ui::personalizations.alert' => Alert::class,
        'tallstack-ui::personalizations.avatar' => Avatar::class,
        'tallstack-ui::personalizations.badge' => Badge::class,
        'tallstack-ui::personalizations.button' => Button::class,
        'tallstack-ui::personalizations.button.circle' => Circle::class,
        'tallstack-ui::personalizations.card' => Card::class,
        'tallstack-ui::personalizations.dialog' => Dialog::class,
        'tallstack-ui::personalizations.dropdown' => Dropdown::class,
        'tallstack-ui::personalizations.dropdown.items' => DropdownItems::class,
        'tallstack-ui::personalizations.error' => Error::class,
        'tallstack-ui::personalizations.errors' => Errors::class,
        'tallstack-ui::personalizations.form.input' => Input::class,
        'tallstack-ui::personalizations.form.label' => Label::class,
        'tallstack-ui::personalizations.form.password' => Password::class,
        'tallstack-ui::personalizations.form.checkbox' => Checkbox::class,
        'tallstack-ui::personalizations.form.radio' => Radio::class,
        'tallstack-ui::personalizations.form.textarea' => Textarea::class,
        'tallstack-ui::personalizations.form.toggle' => Toggle::class,
        'tallstack-ui::personalizations.hint' => Hint::class,
        'tallstack-ui::personalizations.modal' => Modal::class,
        'tallstack-ui::personalizations.select.native' => SelectNative::class,
        'tallstack-ui::personalizations.select.styled' => SelectStyled::class,
        'tallstack-ui::personalizations.tab' => Tab::class,
        'tallstack-ui::personalizations.tab.items' => TabItems::class,
        'tallstack-ui::personalizations.toast' => Toast::class,
        'tallstack-ui::personalizations.tooltip' => Tooltip::class,
        'tallstack-ui::personalizations.wrapper.input' => InputWrapper::class,
        'tallstack-ui::personalizations.wrapper.radio' => RadioWrapper::class,
        'tallstack-ui::personalizations.wrapper.select' => SelectWrapper::class,
    ];

    public function __construct(
        public ?string $component = null
    ) {
        //
    }

    public function alert(): Alert
    {
        return app($this->component(Alert::class));
    }

    public function avatar(): Avatar
    {
        return app($this->component(Avatar::class));
    }

    public function badge(): Badge
    {
        return app($this->component(Badge::class));
    }

    public function block(string|array $name, string|Closure|PersonalizableClass $code = null): PersonalizableContract
    {
        return $this->instance()->block($name, $code);
    }

    public function button(string $component = null): Button|Circle
    {
        $component ??= 'button';

        $class = match ($component) {
            'button' => Button::class,
            'circle' => Circle::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function card(): Card
    {
        return app($this->component(Card::class));
    }

    public function dialog(): Dialog
    {
        return app($this->component(Dialog::class));
    }

    public function dropdown(string $component = null): Dropdown|DropdownItems
    {
        $component ??= 'dropdown';

        $class = match ($component) {
            'dropdown' => Dropdown::class,
            'items' => DropdownItems::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function error(): Error
    {
        return app($this->component(Error::class));
    }

    public function errors(): Errors
    {
        return app($this->component(Errors::class));
    }

    public function form(string $component = null): Input|Label|Password|Checkbox|Radio|Textarea|Toggle
    {
        $component ??= 'input';

        $class = match ($component) {
            'input' => Input::class,
            'label' => Label::class,
            'password' => Password::class,
            'checkbox' => Checkbox::class,
            'radio' => Radio::class,
            'textarea' => Textarea::class,
            'toggle' => Toggle::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function hint(): Hint
    {
        return app($this->component(Hint::class));
    }

    public function instance(): PersonalizableContract
    {
        if (! $this->component) {
            throw new InvalidArgumentException('No component has been set');
        }

        if (str_contains($this->component, 'tallstack-ui::personalizations')) {
            $this->component = str_replace('tallstack-ui::personalizations.', '', $this->component);
        }

        $parts = explode('.', $this->component);
        $main = $parts[0];
        $secondary = $parts[1] ?? null;

        if (! method_exists($this, $main)) {
            throw new InvalidArgumentException("The method [{$main}] is not supported");
        }

        return call_user_func([$this, $main], $main === $secondary ?: $secondary);
    }

    public function modal(): Modal
    {
        return app($this->component(Modal::class));
    }

    public function select(string $component = null): SelectNative|SelectStyled
    {
        $component ??= 'native';

        $class = match ($component) {
            'native' => SelectNative::class,
            'styled' => SelectStyled::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function tab(string $component = null): Tab|TabItems
    {
        $component ??= 'tabs';

        $class = match ($component) {
            'tabs' => Tab::class,
            'items' => TabItems::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function toast(): Toast
    {
        return app($this->component(Toast::class));
    }

    public function tooltip(): Tooltip
    {
        return app($this->component(Tooltip::class));
    }

    public function wrapper(string $component = null): InputWrapper|RadioWrapper|SelectWrapper
    {
        $component ??= 'input';

        $class = match ($component) {
            'input' => InputWrapper::class,
            'radio' => RadioWrapper::class,
            'select' => SelectWrapper::class,
            default => $component,
        };

        return app($this->component($class));
    }

    private function component(string $class): string
    {
        $component = array_search($class, self::PERSONALIZABLES);

        if (! $component) {
            throw new InvalidArgumentException("Component [{$class}] is not allowed to be personalized");
        }

        return $component;
    }
}
