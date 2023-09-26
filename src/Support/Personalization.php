<?php

namespace TasteUi\Support;

use Closure;
use Illuminate\Support\Facades\View as Facade;
use Illuminate\View\View;
use InvalidArgumentException;
use TasteUi\Support\Personalizations\Alert;

class Personalization
{
    public const COMPONENTS = [
        'taste-ui::personalizations.alert' => [
            'personalize' => Alert::class,
            'component' => 'taste-ui::components.alert',
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

    public function block(string $block, string|Closure $code): self
    {
        if (! method_exists($this->instance, $block)) {
            throw new InvalidArgumentException("Block [$block] not found in the personalization of the [$this->component] component");
        }

        Facade::composer($this->component, fn (View $view) => $this->instance->{$block} = is_string($code) ? $code : $code($view->getData()));

        return $this;
    }

    public function get(string $block): ?string
    {
        return $this->instance->{$block}();
    }
}
