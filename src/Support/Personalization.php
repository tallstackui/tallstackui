<?php

namespace TasteUi\Support;

use Closure;
use Illuminate\Support\Facades\View as Facade;
use Illuminate\View\View;
use InvalidArgumentException;
use TasteUi\Contracts\Customizable;
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
use TasteUi\Support\Personalizations\Components\Tooltip;
use TasteUi\Support\Personalizations\Components\Wrapper\Input as InputWrapper;
use TasteUi\Support\Personalizations\Components\Wrapper\Radio as RadioWrapper;
use TasteUi\Support\Personalizations\Contracts\Personalizable as PersonalizableContract;

final class Personalization
{
    public const COMPONENTS = [
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
        'taste-ui::personalizations.toast' => Toast::class,
        'taste-ui::personalizations.tooltip' => Tooltip::class,
        'taste-ui::personalizations.wrapper.input' => InputWrapper::class,
        'taste-ui::personalizations.wrapper.radio' => RadioWrapper::class,
    ];

    public function __construct(
        public string $component,
        private ?string $view = null,
        private ?Customizable $instance = null,
        private ?PersonalizableContract $personalization = null,
    ) {
        if (! str_contains($component, 'taste-ui::personalizations')) {
            $component = 'taste-ui::personalizations.'.$component;
        }

        if (! array_key_exists($component, self::COMPONENTS)) {
            throw new InvalidArgumentException("Personalization [$component] not found");
        }

        $this->personalization = app($component);
        $this->instance = $this->component();
        $this->view = $this->instance->render()->name(); // @phpstan-ignore-line
    }

    /**
     * Set the personalization in the component block.
     *
     * @return $this
     */
    public function block(string $name, string|Closure|PersonalizableClass $code): self
    {
        if (! in_array($name, array_values($this->blocks()))) {
            throw new InvalidArgumentException("Block [$name] is not allowed to be personalized at the [$this->view] component.");
        }

        Facade::composer($this->view, fn (View $view) => $this->personalization->set($name, is_callable($code) ? $code($view->getData()) : $code));

        return $this;
    }

    /**
     * Alias to the `block` method.
     *
     * @return $this
     */
    public function in(string $block, string|Closure|PersonalizableClass $code): self
    {
        return $this->block($block, $code);
    }

    /**
     * Returns the instance of the personalization class.
     */
    public function instance(): PersonalizableContract
    {
        return $this->personalization;
    }

    /**
     * Retrieves the Blade component class instance.
     */
    private function component(): Customizable
    {
        return app($this->personalization->component(), ['ignoreValidations' => true]);
    }

    /**
     * Get all the blocks that can be personalized directly
     * from the component class `tasteUiClasses` method.
     */
    private function blocks(): array
    {
        return array_keys($this->instance->tasteUiClasses());
    }
}
