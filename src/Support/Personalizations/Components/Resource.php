<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as facadeView;
use Illuminate\Support\Str;
use Illuminate\View\View;
use TasteUi\Contracts\Personalizable as PersonalizableClass;
use TasteUi\Support\Personalization;
use TasteUi\Support\Personalizations\Contracts\Personalizable;

/**
 * @property-read Personalization $and
 */
abstract class Resource
{
    private string $view;

    public function __construct(protected ?Personalization $personalization = null, public ?Collection $parts = null)
    {
        $this->parts = collect();
        $this->view = app($this->component(), ['ignoreValidations' => true])->render()->name();
    }

    /**
     * Set the personalization in the component block.
     */
    protected function block(string $name, string|Closure|PersonalizableClass $code): void
    {
        facadeView::composer($this->view, fn (View $view) => $this->set($name, is_callable($code) ? $code($view->getData()) : $code));
    }

    /**
     * This method will be called when you try to personalize a component.
     */
    public function __call(string $method, array $code): static
    {
        $this->validateBlock($this->dotBlock($method));
        $this->block($this->dotBlock($method), ...$code);

        return $this;
    }

    /**
     * Get the component class.
     */
    abstract protected function component(): string;

    /**
     * Set the personalization in the component block.
     */
    protected function set(string $block, string $content): void
    {
        $this->parts[$block] = $content;
    }

    /**
     * Get the personalization in the component block.
     */
    public function get(string $block): ?string
    {
        return data_get($this->parts, $block);
    }

    /**
     * Get all personalization in the component block.
     */
    public function toArray(): array
    {
        return $this->parts->toArray();
    }

    protected function validateBlock(string $block): void
    {
        if (! in_array($block, array_values($blocks = $this->blocks()))) {
            throw new \InvalidArgumentException("Block [$block] is not allowed to be personalized at the [$this->view] component. Alloweds: ".implode(', ', $blocks).'.');
        }
    }

    protected function blocks(): array
    {
        return array_keys(app($this->component(), ['ignoreValidations' => true])->tasteUiClasses());
    }

    /**
     * return instance of personalization.
     */
    public function __get(string $name): ?Personalization
    {

        if ($name === 'and') {
            return $this->personalization;
        }

        throw new \RuntimeException("Property {$name} does not exist.");
    }

    protected function dotBlock(string $method): string
    {
        return Str::of($method)->snake()->replace('_', '.')->toString();
    }
}
