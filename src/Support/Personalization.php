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
use TasteUi\Support\Personalizations\Components\Tooltip;

class Personalization implements Arrayable
{
    public const COMPONENTS = [
        'taste-ui::personalizations.alert' => [
            'personalize' => Alert::class,
            'component' => 'taste-ui::components.alert',
        ],
        'taste-ui::personalizations.badge' => [
            'personalize' => Badge::class,
            'component' => 'taste-ui::components.badge',
        ],
        'taste-ui::personalizations.card' => [
            'personalize' => Card::class,
            'component' => 'taste-ui::components.card',
        ],
        'taste-ui::personalizations.hint' => [
            'personalize' => Hint::class,
            'component' => 'taste-ui::components.hint',
        ],
        'taste-ui::personalizations.error' => [
            'personalize' => Error::class,
            'component' => 'taste-ui::components.error',
        ],
        'taste-ui::personalizations.errors' => [
            'personalize' => Errors::class,
            'component' => 'taste-ui::components.errors',
        ],
        'taste-ui::personalizations.tooltip' => [
            'personalize' => Tooltip::class,
            'component' => 'taste-ui::components.tooltip',
        ],
        'taste-ui::personalizations.avatar' => [
            'personalize' => Avatar::class,
            'component' => 'taste-ui::components.avatar',
        ],
        'taste-ui::personalizations.button' => [
            'personalize' => Index::class,
            'component' => 'taste-ui::components.buttons.index',
        ],
        'taste-ui::personalizations.button.circle' => [
            'personalize' => Circle::class,
            'component' => 'taste-ui::components.buttons.circle',
        ],
        'taste-ui::personalizations.form.input' => [
            'personalize' => Input::class,
            'component' => 'taste-ui::components.form.input',
        ],
        'taste-ui::personalizations.form.label' => [
            'personalize' => Label::class,
            'component' => 'taste-ui::components.form.label',
        ],
        'taste-ui::personalizations.form.password' => [
            'personalize' => Password::class,
            'component' => 'taste-ui::components.form.password',
        ],
        'taste-ui::personalizations.form.checkbox' => [
            'personalize' => Checkbox::class,
            'component' => 'taste-ui::components.form.checkbox',
        ],
        'taste-ui::personalizations.form.radio' => [
            'personalize' => Radio::class,
            'component' => 'taste-ui::components.form.radio',
        ],
        'taste-ui::personalizations.form.textarea' => [
            'personalize' => Textarea::class,
            'component' => 'taste-ui::components.form.textarea',
        ],
        'taste-ui::personalizations.form.toggle' => [
            'personalize' => Toggle::class,
            'component' => 'taste-ui::components.form.toggle',
        ],
    ];

    public function __construct(
        public ?string $component = null,
        public ?object $instance = null,
    ) {
        if (! array_key_exists($this->component, self::COMPONENTS)) {
            throw new InvalidArgumentException("Personalization [$this->component] not found");
        }

        $this->instance = app($this->component);
        $this->component = self::COMPONENTS[$this->component]['component'];
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
