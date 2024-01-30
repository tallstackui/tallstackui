<?php

namespace TallStackUi\Foundation\Personalization;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as Facade;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;
use TallStackUi\Contracts\Personalizable;

/**
 * @internal This class is not meant to be used directly.
 *
 * @property-read Personalization $and
 */
class PersonalizationResources
{
    public function __construct(
        private readonly ?string $component = null,
        private ?string $block = null,
        private ?Collection $originals = null,
        private readonly ?Collection $interactions = new Collection(),
        private ?Collection $parts = new Collection(),
    ) {
        $this->originals = collect($this->personalization());
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
        $this->interactions->put('append', $content);

        $this->set($this->block);

        return $this;
    }

    public function block(string|array $name, string|Closure|Personalizable|null $code = null): self
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
        $this->interactions->put('prepend', $content);

        $this->set($this->block);

        return $this;
    }

    public function remove(string|array $class): self
    {
        $this->interactions->put('remove', is_array($class) ? $class : [$class]);

        $this->set($this->block);

        return $this;
    }

    public function replace(string|array $from, ?string $to = null): self
    {
        $this->interactions->put('replace', is_array($from) ? $from : [$from => $to]);

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

    private function compile(string $block, string|Closure|Personalizable|null $code = null): void
    {
        $view = $this->personalization(true)->blade()->name(); // @phpstan-ignore-line

        if (! in_array($block, $blocks = $this->blocks())) {
            $component = str_replace('tallstack-ui::components.', '', (string) $view);

            throw new InvalidArgumentException("Component [$component] does not have the block [$block] to be personalized. Alloweds: ".implode(', ', $blocks));
        }

        Facade::composer($view, fn (View $view) => $this->set($block, is_callable($code) ? $code($view->getData()) : $code));
    }

    private function personalization(bool $instance = false): array|object
    {
        $class = app($this->component);

        if ($instance) {
            return $class;
        }

        return $class->personalization();
    }

    private function set(string $block, ?string $content = null): void
    {
        foreach ($this->interactions->get('replace', []) as $old => $new) {
            $this->originals->put($block, str_replace($old, $new, (string) $this->originals->get($block)));
        }

        if ($append = $this->interactions->get('append')) {
            $this->originals->put($block, $this->originals->get($block).' '.$append);
        }

        if ($prepend = $this->interactions->get('prepend')) {
            $this->originals->put($block, $prepend.' '.$this->originals->get($block));
        }

        foreach ($this->interactions->get('remove', []) as $class) {
            $this->originals->put($block, str_replace($class, '', (string) $this->originals->get($block)));
        }

        $this->parts[$block] = trim($content ?? str($this->originals->get($block))->squish());

        $this->interactions->forget('replace');
        $this->interactions->forget('append');
        $this->interactions->forget('prepend');
        $this->interactions->forget('remove');
    }
}
