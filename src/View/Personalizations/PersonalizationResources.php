<?php

namespace TallStackUi\View\Personalizations;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as FacadeView;
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

    public function block(string|array $name, string|Closure|Personalizable $code = null): static
    {
        if (is_string($name) && ! $code) {
            throw new InvalidArgumentException('The second argument must be set when the first is a string');
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

    public function toArray(): array
    {
        return $this->parts->toArray();
    }

    protected function blocks(): array
    {
        // The [ignoreValidations => true] used here is a way to ignore possible validations
        // that may exist in the component class. This is necessary because the component class
        // is instantiated at this point, so if there are validations to be applied we would have exceptions.
        return array_keys(app($this->component, ['ignoreValidations' => true])->personalization());
    }

    protected function set(string $block, string $content): void
    {
        $this->parts[$block] = $content;
    }

    private function compile(string $block, string|Closure|Personalizable $code): void
    {
        // The [ignoreValidations => true] used here is a way to ignore possible validations
        // that may exist in the component class. This is necessary because the component class
        // is instantiated at this point, so if there are validations to be applied we would have exceptions.
        $view = app($this->component, ['ignoreValidations' => true])->render()->name();

        if (! in_array($block, array_values($blocks = $this->blocks()))) {
            $component = str_replace('tallstack-ui::personalizations.', '', $view);

            throw new InvalidArgumentException("Component [$component] does not have the block [$block] to be personalized. Alloweds: ".implode(', ', $blocks));
        }

        FacadeView::composer($view, fn (View $view) => $this->set($block, is_callable($code) ? $code($view->getData()) : $code));
    }
}
