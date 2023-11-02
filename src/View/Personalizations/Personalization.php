<?php

namespace TallStackUi\View\Personalizations;

use Closure;
use InvalidArgumentException;
use TallStackUi\Contracts\Personalizable;
use TallStackUi\View\Components\Alert;
use TallStackUi\View\Components\Avatar;
use TallStackUi\View\Components\Badge;
use TallStackUi\View\Components\Button\Button;
use TallStackUi\View\Components\Button\Circle;
use TallStackUi\View\Components\Card;
use TallStackUi\View\Components\Dropdown\Dropdown;
use TallStackUi\View\Components\Dropdown\Items as DropdownItems;
use TallStackUi\View\Components\Errors;
use TallStackUi\View\Components\Form\Checkbox;
use TallStackUi\View\Components\Form\Error;
use TallStackUi\View\Components\Form\Hint;
use TallStackUi\View\Components\Form\Input;
use TallStackUi\View\Components\Form\Label;
use TallStackUi\View\Components\Form\Password;
use TallStackUi\View\Components\Form\Radio;
use TallStackUi\View\Components\Form\Textarea;
use TallStackUi\View\Components\Form\Toggle;
use TallStackUi\View\Components\Interaction\Dialog;
use TallStackUi\View\Components\Interaction\Toast;
use TallStackUi\View\Components\Modal;
use TallStackUi\View\Components\Select\Native as SelectNative;
use TallStackUi\View\Components\Select\Styled as SelectStyled;
use TallStackUi\View\Components\Tab\Items as TabItems;
use TallStackUi\View\Components\Tab\Tab;
use TallStackUi\View\Components\Tooltip;
use TallStackUi\View\Components\Wrapper\Input as InputWrapper;
use TallStackUi\View\Components\Wrapper\Radio as RadioWrapper;
use Throwable;

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
        'tallstack-ui::personalizations.errors' => Errors::class,
        'tallstack-ui::personalizations.form.input' => Input::class,
        'tallstack-ui::personalizations.form.error' => Error::class,
        'tallstack-ui::personalizations.form.hint' => Hint::class,
        'tallstack-ui::personalizations.form.label' => Label::class,
        'tallstack-ui::personalizations.form.password' => Password::class,
        'tallstack-ui::personalizations.form.checkbox' => Checkbox::class,
        'tallstack-ui::personalizations.form.radio' => Radio::class,
        'tallstack-ui::personalizations.form.textarea' => Textarea::class,
        'tallstack-ui::personalizations.form.toggle' => Toggle::class,
        'tallstack-ui::personalizations.modal' => Modal::class,
        'tallstack-ui::personalizations.select.native' => SelectNative::class,
        'tallstack-ui::personalizations.select.styled' => SelectStyled::class,
        'tallstack-ui::personalizations.tab' => Tab::class,
        'tallstack-ui::personalizations.tab.items' => TabItems::class,
        'tallstack-ui::personalizations.toast' => Toast::class,
        'tallstack-ui::personalizations.tooltip' => Tooltip::class,
        'tallstack-ui::personalizations.wrapper.input' => InputWrapper::class,
        'tallstack-ui::personalizations.wrapper.radio' => RadioWrapper::class,
    ];

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

    public function block(string|array $name, string|Closure|Personalizable $code = null): PersonalizationResources
    {
        return $this->instance()->block($name, $code);
    }

    public function button(string $component = null): PersonalizationResources
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

    public function dialog(): PersonalizationResources
    {
        return app($this->component(Dialog::class));
    }

    public function dropdown(string $component = null): PersonalizationResources
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

    public function form(string $component = null): PersonalizationResources
    {
        $component ??= 'input';

        $class = match ($component) {
            'error' => Error::class,
            'input' => Input::class,
            'hint' => Hint::class,
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

    public function instance(): PersonalizationResources
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

    public function modal(): PersonalizationResources
    {
        return app($this->component(Modal::class));
    }

    public function select(string $component = null): PersonalizationResources
    {
        $component ??= 'native';

        $class = match ($component) {
            'native' => SelectNative::class,
            'styled' => SelectStyled::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function tab(string $component = null): PersonalizationResources
    {
        $component ??= 'tabs';

        $class = match ($component) {
            'tabs' => Tab::class,
            'items' => TabItems::class,
            default => $component,
        };

        return app($this->component($class));
    }

    public function toast(): PersonalizationResources
    {
        return app($this->component(Toast::class));
    }

    public function tooltip(): PersonalizationResources
    {
        return app($this->component(Tooltip::class));
    }

    public function wrapper(string $component = null): PersonalizationResources
    {
        $component ??= 'input';

        $class = match ($component) {
            'input' => InputWrapper::class,
            'radio' => RadioWrapper::class,
            default => $component,
        };

        return app($this->component($class));
    }

    /** @throws Throwable */
    private function component(string $class): string
    {
        $component = array_search($class, self::PERSONALIZABLES);

        throw_if(! $component, new InvalidArgumentException("Component [{$class}] is not allowed to be personalized"));

        return $component;
    }
}
