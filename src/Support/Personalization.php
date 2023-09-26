<?php

namespace TasteUi\Support;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\View as Facade;
use Illuminate\View\View;
use InvalidArgumentException;
use TasteUi\Contracts\Personalizable;
use TasteUi\Support\Personalizations\Alert;
use TasteUi\Support\Personalizations\Badge;
use TasteUi\Support\Personalizations\Card;

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
