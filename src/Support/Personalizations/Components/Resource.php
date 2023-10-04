<?php

namespace TasteUi\Support\Personalizations\Components;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as facadeView;
use Illuminate\Support\Str;
use Illuminate\View\View;
use InvalidArgumentException;
use TasteUi\Contracts\Personalizable as PersonalizableClass;
use TasteUi\Support\Personalization;

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
    private function factory(string $name, string|Closure|PersonalizableClass $code): void
    {
        $this->validate($name);

        facadeView::composer($this->view, fn (View $view) => $this->set($name, is_callable($code) ? $code($view->getData()) : $code));
    }

    public function block(string|array $name, string|Closure|PersonalizableClass $code = ''): static
    {
        if (is_string($name) && empty($code)) {
            throw new InvalidArgumentException('The second argument cannot be empty');
        }

        if (is_array($name) && ! empty($code)) {
            throw new InvalidArgumentException('The second argument must be empty');
        }

        if (is_array($name) && empty($code)) {
            return $this->each($name);
        }

        $this->factory($name, $code);

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

    protected function validate(string $block): void
    {
        if (! in_array($block, array_values($blocks = $this->blocks()))) {
            throw new InvalidArgumentException("Block [$block] is not allowed to be personalized at the [$this->view] component. Alloweds: ".implode(', ', $blocks).'.');
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

    /**
     * Set the personalization in the component block.
     */
    private function each(array $blocks): static
    {
        foreach ($blocks as $name => $code) {
            $this->factory($name, $code);
        }

        return $this;
    }

    protected function dotBlock(string $method): string
    {
        return Str::of($method)->snake()->replace('_', '.')->toString();
    }
}
