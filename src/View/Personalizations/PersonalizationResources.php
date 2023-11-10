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
        private ?array $replaces = [],
        private ?string $remove = null,
        private ?string $append = null,
        private ?string $prepend = null,
        private ?Collection $parts = new Collection(),
    ) {
        //
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
        $this->append = $content;

        return $this;
    }

    public function block(string|array $name, string|null|Closure|Personalizable $code = null): self
    {
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
        $this->prepend = $content;

        return $this;
    }

    public function remove(string $class): self
    {
        $this->remove = $class;

        return $this;
    }

    public function replace(string|array $from, string $to = null): self
    {
        $this->replaces = is_array($from) ? $from : [$from => $to];

        return $this;
    }

    public function toArray(): array
    {
        return $this->parts->toArray();
    }

    protected function blocks(): array
    {
        return array_keys($this->personalization());
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

    protected function set(string $block, string $content = null): void
    {
        $original = $this->personalization();

        if (! empty($this->replaces)) {
            foreach ($this->replaces as $old => $new) {
                $original[$block] = str_replace($old, $new, $original[$block]);
            }

            $original[$block] = str($original[$block])->squish();
        }

        if (! empty($this->append)) {
            $original[$block] .= ' '.$this->append;
        }

        if (! empty($this->prepend)) {
            $original[$block] = $this->prepend.' '.$original[$block];
        }

        if (! empty($this->remove)) {
            $original[$block] = str_replace($this->remove, '', $original[$block]);
            $original[$block] = str($original[$block])->squish();
        }

        $this->parts[$block] = trim($content ?? $original[$block]);
    }

    private function compile(string $block, string|null|Closure|Personalizable $code = null): void
    {
        $view = $this->personalization(true)->render()->name();

        if (! in_array($block, array_values($blocks = $this->blocks()))) {
            $component = str_replace('tallstack-ui::personalizations.', '', $view);

            throw new InvalidArgumentException("Component [$component] does not have the block [$block] to be personalized. Alloweds: ".implode(', ', $blocks));
        }

        Facade::composer($view, fn (View $view) => $this->set($block, is_callable($code) ? $code($view->getData()) : $code));
    }
}
