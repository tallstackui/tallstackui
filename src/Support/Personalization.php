<?php

namespace TasteUi\Support;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\View as Facade;
use Illuminate\View\View;
use InvalidArgumentException;
use TasteUi\Contracts\Personalizable;
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
use TasteUi\Support\Personalizations\Components\Modal;
use TasteUi\Support\Personalizations\Components\Select\Select;
use TasteUi\Support\Personalizations\Components\Tooltip;

class Personalization implements Arrayable
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
        'taste-ui::personalizations.tooltip' => Tooltip::class,
    ];

    public function __construct(
        public ?string $component = null,
        public ?object $instance = null,
    ) {
        if (! str_contains($this->component, 'taste-ui::personalizations')) {
            $this->component = 'taste-ui::personalizations.'.$this->component;
        }

        if (! array_key_exists($this->component, self::COMPONENTS)) {
            throw new InvalidArgumentException("Personalization [$this->component] not found");
        }

        $this->instance = app($this->component);

        $personalizable = self::COMPONENTS[$this->component];

        $personalizable = new $personalizable();
        $this->component = app($personalizable->component())->render()->name();
    }

    public function block(string $block, string|Closure|Personalizable $code): self
    {
        if (! in_array($block, array_values($this->instance::EDITABLES))) {
            throw new InvalidArgumentException("Block [$block] is not allowed to be personalized at the [$this->component] component.");
        }

        Facade::composer($this->component, fn (View $view) => $this->instance->set($block, is_callable($code) ? $code($view->getData()) : $code));

        return $this;
    }

    public function get(string $block): ?string
    {
        return $this->instance->get($block);
    }

    public function toArray(): array
    {
        return $this->instance->toArray();
    }
}
