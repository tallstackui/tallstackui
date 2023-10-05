<?php

namespace TasteUi\Support;

use Closure;
use InvalidArgumentException;
use TasteUi\Contracts\Personalizable as PersonalizableClass;
use TasteUi\Support\Personalizations\Components\Alert;
use TasteUi\Support\Personalizations\Components\Avatar;
use TasteUi\Support\Personalizations\Components\Badge;
use TasteUi\Support\Personalizations\Components\Button\Circle as ButtonCircle;
use TasteUi\Support\Personalizations\Components\Button\Index as Button;
use TasteUi\Support\Personalizations\Components\Card;
use TasteUi\Support\Personalizations\Components\Error;
use TasteUi\Support\Personalizations\Components\Errors;
use TasteUi\Support\Personalizations\Components\Form\Checkbox;
use TasteUi\Support\Personalizations\Components\Form\Input;
use TasteUi\Support\Personalizations\Components\Form\Label;
use TasteUi\Support\Personalizations\Components\Form\Password;
use TasteUi\Support\Personalizations\Components\Form\Radio;
use TasteUi\Support\Personalizations\Components\Form\Textarea;
use TasteUi\Support\Personalizations\Components\Form\Toggle;
use TasteUi\Support\Personalizations\Components\Hint;
use TasteUi\Support\Personalizations\Components\Interactions\Dialog;
use TasteUi\Support\Personalizations\Components\Interactions\Toast;
use TasteUi\Support\Personalizations\Components\Modal;
use TasteUi\Support\Personalizations\Components\Select\Searchable as SelectSearchable;
use TasteUi\Support\Personalizations\Components\Select\Select;
use TasteUi\Support\Personalizations\Components\Select\Styled as SelectStyled;
use TasteUi\Support\Personalizations\Components\Tabs\Index as TabsWrapper;
use TasteUi\Support\Personalizations\Components\Tabs\Item as TabItems;
use TasteUi\Support\Personalizations\Components\Tooltip;
use TasteUi\Support\Personalizations\Components\Wrapper\Input as InputWrapper;
use TasteUi\Support\Personalizations\Components\Wrapper\Radio as RadioWrapper;
use TasteUi\Support\Personalizations\Components\Wrapper\Select as SelectWrapper;
use TasteUi\Support\Personalizations\Contracts\Personalizable as PersonalizableContract;

class Personalization
{
    public const PERSONALIZABLES = [
        'taste-ui::personalizations.alert' => Alert::class,
        'taste-ui::personalizations.avatar' => Avatar::class,
        'taste-ui::personalizations.badge' => Badge::class,
        'taste-ui::personalizations.button' => Button::class,
        'taste-ui::personalizations.button.circle' => ButtonCircle::class,
        'taste-ui::personalizations.card' => Card::class,
        'taste-ui::personalizations.dialog' => Dialog::class,
        'taste-ui::personalizations.error' => Error::class,
        'taste-ui::personalizations.errors' => Errors::class,
        'taste-ui::personalizations.form.input' => Input::class,
        'taste-ui::personalizations.form.label' => Label::class,
        'taste-ui::personalizations.form.password' => Password::class,
        'taste-ui::personalizations.form.checkbox' => Checkbox::class,
        'taste-ui::personalizations.form.radio' => Radio::class,
        'taste-ui::personalizations.form.textarea' => Textarea::class,
        'taste-ui::personalizations.form.toggle' => Toggle::class,
        'taste-ui::personalizations.hint' => Hint::class,
        'taste-ui::personalizations.modal' => Modal::class,
        'taste-ui::personalizations.select' => Select::class,
        'taste-ui::personalizations.select.searchable' => SelectSearchable::class,
        'taste-ui::personalizations.select.styled' => SelectStyled::class,
        'taste-ui::personalizations.tabs' => TabsWrapper::class,
        'taste-ui::personalizations.tabs.items' => TabItems::class,
        'taste-ui::personalizations.toast' => Toast::class,
        'taste-ui::personalizations.tooltip' => Tooltip::class,
        'taste-ui::personalizations.wrapper.input' => InputWrapper::class,
        'taste-ui::personalizations.wrapper.radio' => RadioWrapper::class,
        'taste-ui::personalizations.wrapper.select' => SelectWrapper::class,
    ];

    public function __construct(
        public ?string $component = null
    ) {
        //
    }

    public function block(string|array $name, string|Closure|PersonalizableClass $code = null): PersonalizableContract
    {
        return $this->instance()->block($name, $code);
    }

    public function instance(): PersonalizableContract
    {
        if (! $this->component) {
            throw new InvalidArgumentException('No component has been set');
        }

        if (str_contains($this->component, 'taste-ui::personalizations')) {
            $this->component = str_replace('taste-ui::personalizations.', '', $this->component);
        }

        $parts = explode('.', $this->component);
        $main = $parts[0];
        $secondary = $parts[1] ?? null;

        if (! method_exists($this, $main)) {
            throw new InvalidArgumentException("The method [{$main}] is not supported");
        }

        return call_user_func([$this, $main], $main === $secondary ?: $secondary);
    }

    public function alert(): Alert
    {
        return app($this->component(Alert::class));
    }

    public function modal(): Modal
    {
        return app($this->component(Modal::class));
    }

    public function button(string $component = null): Button|ButtonCircle
    {
        $class = $component === 'circle' ? ButtonCircle::class : Button::class;

        return app($this->component($class));
    }

    public function avatar(): Avatar
    {
        return app($this->component(Avatar::class));
    }

    public function badge(): Badge
    {
        return app($this->component(Badge::class));
    }

    public function card(): Card
    {
        return app($this->component(Card::class));
    }

    public function dialog(): Dialog
    {
        return app($this->component(Dialog::class));
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

    public function select(string $component = null): Select|SelectSearchable|SelectStyled
    {
        $component ??= 'select';

        $class = match ($component) {
            'select' => Select::class,
            'searchable' => SelectSearchable::class,
            'styled' => SelectStyled::class,
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

    public function tabs(string $component = null): TabsWrapper|TabItems
    {
        $component ??= 'tabs';

        $class = match ($component) {
            'tabs' => TabsWrapper::class,
            'items' => TabItems::class,
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
