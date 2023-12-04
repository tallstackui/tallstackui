<?php

namespace TallStackUi\View\Personalizations;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as Facade;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;
use TallStackUi\Contracts\Personalizable;
use TallStackUi\View\Personalizations\Contracts\PersonalizableResources;

/**
 * @internal This class is not meant to be used directly.
 *
 * @property-read Personalization $and
 */
class PersonalizationResources implements PersonalizableResources
{
    public function __construct(
        private readonly ?string $component = null,
        private ?array $interactions = [],
        private ?array $originals = [],
        private ?string $block = null,
        private ?Collection $parts = new Collection(),
    ) {
        $this->originals = $this->personalization();
    }

    public function __get(string $property): Personalization
    {
        if ($property === 'and') {
            return $this->and();
        }

        throw new RuntimeException("Property [{$property}] does not exist.");
    }

    public function and(): Personalization
    {
        return new Personalization();
    }

    public function append(string $content): self
    {
        $this->interactions['append'] = $content;

        $this->set($this->block);

        return $this;
    }

    public function block(string|array $name, string|Closure|Personalizable $code = null): self
    {
        // If the $code was not set, then we
        // are interacting with the helpers.
        if (is_string($name) && ! $code) {
            $this->block = $name;

            return $this;
        }

        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->compile($key, $value);
            }
        } else {
            $this->compile($name, $code);
        }

        return $this;
    }

    public function get(string $block): ?string
    {
        return data_get($this->parts, $block);
    }

    public function prepend(string $content): self
    {
        $this->interactions['prepend'] = $content;

        $this->set($this->block);

        return $this;
    }

    public function remove(string|array $class): self
    {
        $this->interactions['remove'] = is_array($class) ? $class : [$class];

        $this->set($this->block);

        return $this;
    }

    public function replace(string|array $from, string $to = null): self
    {
        $this->interactions['replace'] = is_array($from) ? $from : [$from => $to];

        $this->set($this->block);

        return $this;
    }

    public function toArray(): array
    {
        return $this->parts->toArray();
    }

    private function blocks(): array
    {
        return array_keys($this->personalization());
    }

    private function compile(string $block, string|Closure|Personalizable $code = null): void
    {
        $view = $this->personalization(true)->render()->name(); // @phpstan-ignore-line

        if (! in_array($block, array_values($blocks = $this->blocks()))) {
            $component = str_replace('tallstack-ui::components.', '', $view);

            throw new InvalidArgumentException("Component [$component] does not have the block [$block] to be personalized. Alloweds: ".implode(', ', $blocks));
        }

        Facade::composer($view, fn (View $view) => $this->set($block, is_callable($code) ? $code($view->getData()) : $code));
    }

    private function personalization(bool $instance = false): array|object
    {
        // The [ignoreValidations => true] used here is a way to ignore possible validations
        // that may exist in the component class. This is necessary because the component class
        // is instantiated at this point, so if there are validations to be applied we would have exceptions.
        $class = app($this->component, ['ignoreValidations' => true]);

        if ($instance) {
            return $class;
        }

        return $class->personalization();
    }

    private function set(string $block, string $content = null): void
    {
        $original = collect($this->parts->isEmpty() ? $this->originals : $this->parts->toArray());

        $replace = $this->interactions['replace'] ?? [];
        $append = $this->interactions['append'] ?? null;
        $prepend = $this->interactions['prepend'] ?? null;
        $remove = $this->interactions['remove'] ?? [];

        foreach ($replace as $old => $new) {
            $original->put($block, str_replace($old, $new, $original->get($block) ?? $this->originals[$block]));
        }

        if ($append) {
            $original->put($block, ($original->get($block) ?? $this->originals[$block]).' '.$append);
        }

        if ($prepend) {
            $original->put($block, $prepend.' '.($original->get($block) ?? $this->originals[$block]));
        }

        foreach ($remove as $class) {
            $original->put($block, str_replace($class, '', ($original->get($block) ?? $this->originals[$block])));
        }

        $this->parts[$block] = trim($content ?? str($original->get($block))->squish());
    }
}
