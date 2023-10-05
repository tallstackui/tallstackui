<?php

namespace TasteUi\Support;

use Closure;
use InvalidArgumentException;
use TasteUi\Contracts\Personalizable as PersonalizableClass;
use TasteUi\Support\Personalizations\Components\Alert;
use TasteUi\Support\Personalizations\Components\Avatar;
use TasteUi\Support\Personalizations\Components\Badge;
use TasteUi\Support\Personalizations\Components\Button\Circle;
use TasteUi\Support\Personalizations\Components\Button\Index;
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
use TasteUi\Support\Personalizations\Components\Select\Searchable;
use TasteUi\Support\Personalizations\Components\Select\Select;
use TasteUi\Support\Personalizations\Components\Select\Styled;
use TasteUi\Support\Personalizations\Components\Tabs\Index as TabWrapper;
use TasteUi\Support\Personalizations\Components\Tabs\Item as TabItem;
use TasteUi\Support\Personalizations\Components\Tooltip;
use TasteUi\Support\Personalizations\Components\Wrapper\Input as InputWrapper;
use TasteUi\Support\Personalizations\Components\Wrapper\Radio as RadioWrapper;
use TasteUi\Support\Personalizations\Components\Wrapper\Select as SelectWrapper;
use TasteUi\Support\Personalizations\Contracts\Personalizable as PersonalizableContract;

final class Personalization
{
    public const PERSONALIZABLES = [
        'taste-ui::personalizations.alert' => Alert::class,
        'taste-ui::personalizations.avatar' => Avatar::class,
        'taste-ui::personalizations.badge' => Badge::class,
        'taste-ui::personalizations.button' => Index::class,
        'taste-ui::personalizations.button.circle' => Circle::class,
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
        'taste-ui::personalizations.select.searchable' => Searchable::class,
        'taste-ui::personalizations.select.styled' => Styled::class,
        'taste-ui::personalizations.tabs' => TabWrapper::class,
        'taste-ui::personalizations.tabs.item' => TabItem::class,
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

    public function block(string|array $name, string|Closure|PersonalizableClass $code = ''): PersonalizableContract
    {
        return $this->instance()->block($name, $code);
    }

    public function instance(): PersonalizableContract
    {
        if (! $this->component) {
            throw new InvalidArgumentException('No component has been set.');
        }

        if (str_contains($this->component, 'taste-ui::personalizations')) {
            $this->component = str_replace('taste-ui::personalizations.', '', $this->component);
        }

        $method = str($this->component)
            ->replace('.', '_')
            ->camel()
            ->toString();

        if (! method_exists($this, $method)) {
            throw new InvalidArgumentException("The method [{$method}] is not supported.");
        }

        return call_user_func([$this, $method]);
    }

    public function alert(): Alert
    {
        return app($this->component(Alert::class));
    }

    public function modal(): Modal
    {
        return app($this->component(Modal::class));
    }

    public function button(): Index
    {
        return app($this->component(Index::class));
    }

    public function avatar(): Avatar
    {
        return app($this->component(Avatar::class));
    }

    public function badge(): Badge
    {
        return app($this->component(Badge::class));
    }

    public function buttonCircle(): Circle
    {
        return app($this->component(Circle::class));
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

    public function formInput(): Input
    {
        return app($this->component(Input::class));
    }

    public function formLabel(): Label
    {
        return app($this->component(Label::class));
    }

    public function formPassword(): Password
    {
        return app($this->component(Password::class));
    }

    public function formCheckbox(): Checkbox
    {
        return app($this->component(Checkbox::class));
    }

    public function formRadio(): Radio
    {
        return app($this->component(Radio::class));
    }

    public function formTextarea(): Textarea
    {
        return app($this->component(Textarea::class));
    }

    public function formToggle(): Toggle
    {
        return app($this->component(Toggle::class));
    }

    public function hint(): Hint
    {
        return app($this->component(Hint::class));
    }

    public function select(): Select
    {
        return app($this->component(Select::class));
    }

    public function selectSearchable(): Searchable
    {
        return app($this->component(Searchable::class));
    }

    public function selectStyled(): Styled
    {
        return app($this->component(Styled::class));
    }

    public function toast(): Toast
    {
        return app($this->component(Toast::class));
    }

    public function tooltip(): Tooltip
    {
        return app($this->component(Tooltip::class));
    }

    public function wrapperInput(): InputWrapper
    {
        return app($this->component(InputWrapper::class));
    }

    public function wrapperRadio(): RadioWrapper
    {
        return app($this->component(RadioWrapper::class));
    }

    public function wrapperSelect(): SelectWrapper
    {
        return app($this->component(SelectWrapper::class));
    }

    public function tabs(): TabWrapper
    {
        return app($this->component(TabWrapper::class));
    }

    public function tabsItem(): TabItem
    {
        return app($this->component(TabItem::class));
    }

    private function component(string $class): string
    {
        return array_search($class, self::PERSONALIZABLES);
    }
}
