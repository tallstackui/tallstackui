<?php

namespace TallStackUi\Foundation\Personalization;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as Facade;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;

/**
 * @internal
 *
 * @property-read Personalization $and
 */
class PersonalizationFactory
{
    /**
     * Block name to be personalized.
     */
    public ?string $block = null;

    /**
     * Original classes of the component with changes applied.
     */
    private readonly Collection $changes;

    /**
     * Interactions, for when we are personalizing without $code for the block.
     */
    private Collection $interactions;

    /**
     * Parts of the component personalization.
     */
    private Collection $parts;

    public function __construct(public readonly string $component, private readonly ?string $scope = null)
    {
        $this->interactions = collect();
        $this->parts = collect();
        $this->changes = collect(app($this->component)->personalization());
    }

    /**
     * Creating ability to use Pest's style: ->and->block('name', 'content').
     */
    public function __get(string $property): Personalization
    {
        if ($property === 'and') {
            return $this->and();
        }

        throw new RuntimeException("Property [{$property}] does not exist.");
    }

    /**
     * Personalize sequentially creating a new instance of the Personalization class.
     */
    public function and(): Personalization
    {
        return new Personalization;
    }

    /**
     * Append content to the block.
     *
     * @return $this
     */
    public function append(string $content): self
    {
        $this->interactions->put('append', $content);

        $this->compile();

        return $this;
    }

    /**
     * Interact with the block to start the personalization.
     *
     * @return $this
     */
    public function block(string|array $name, string|callable|null $code = null): self
    {
        // If the $code was not set, then we
        // are interacting with the shortcuts.
        if (is_string($name) && is_null($code)) {
            $this->block = $name;

            return $this;
        }

        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->composer($key, $value);
            }
        } else {
            $this->composer($name, $code);
        }

        return $this;
    }

    public function get(string $block): ?string
    {
        return data_get($this->parts, $block);
    }

    /**
     * Prepend content to the block.
     *
     * @return $this
     */
    public function prepend(string $content): self
    {
        $this->interactions->put('prepend', $content);

        $this->compile();

        return $this;
    }

    /**
     * Remove content to the block.
     *
     * @return $this
     */
    public function remove(string|array $class): self
    {
        $this->interactions->put('remove', Arr::wrap($class));

        $this->compile();

        return $this;
    }

    /**
     * Replace content to the block.
     *
     * @return $this
     */
    public function replace(string|array $from, ?string $to = null): self
    {
        $this->interactions->put('replace', is_array($from) ? $from : [$from => $to]);

        $this->compile();

        return $this;
    }

    /**
     * Get the parts as array.
     */
    public function toArray(): array
    {
        return $this->parts->toArray();
    }

    /**
     * Get all the blocks available for personalization in the component.
     */
    private function blocks(): array
    {
        return array_keys(app($this->component)->personalization());
    }

    /**
     * Compile the personalization.
     */
    private function compile(?string $block = null, ?string $content = null): void
    {
        $block ??= $this->block;

        foreach ($this->interactions->get('replace', []) as $old => $new) {
            $this->changes->put($block, str_replace($old, $new, (string) $this->changes->get($block)));
        }

        if ($append = $this->interactions->get('append')) {
            $this->changes->put($block, $this->changes->get($block).' '.$append);
        }

        if ($prepend = $this->interactions->get('prepend')) {
            $this->changes->put($block, $prepend.' '.$this->changes->get($block));
        }

        foreach ($this->interactions->get('remove', []) as $class) {
            $this->changes->put($block, str_replace($class, '', (string) $this->changes->get($block)));
        }

        $content = fn () => trim($content ?? str($this->changes->get($block))->squish());

        $parts = $this->parts->toArray();

        // When scoped we need to set the parts as a multidimensional array.
        if ($this->scope) {
            data_set($parts, $this->scope.'.'.$block, $content());

            $this->parts = collect($parts);
        } else {
            $this->parts->put($block, $content());
        }

        // Flushing
        $this->interactions = collect();
    }

    /**
     * Composes the personalization in View::composer for cases where
     * we are not interacting with the customization method without $code.
     */
    private function composer(string $block, string|callable|null $code = null): void
    {
        $view = app($this->component)->blade()->name();

        if (! in_array($block, $blocks = $this->blocks())) {
            $component = str_replace('tallstack-ui::components.', '', (string) $view);

            throw new InvalidArgumentException("Component [$component] does not have the block [$block] to be personalized. Alloweds: ".implode(', ', $blocks));
        }

        // We leave everything prepared and linked with the
        // Blade file associated with the component so that
        // the $classes() call obtains the personalization.
        Facade::composer($view, fn (View $view) => $this->compile($block, is_callable($code) ? app()->call(fn () => $code($view->getData())) : $code));
    }
}
