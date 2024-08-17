<?php

namespace TallStackUi\Foundation\Colors\Traits;

use Illuminate\Support\Arr;
use Illuminate\View\InvokableComponentVariable;
use ReflectionClass;
use ReflectionMethod;

trait OverrideColors
{
    protected array $overrides = [];

    /**
     * This method is used to build the strings to data_get format:
     * Using: $this->getter('foo', 'bar') will result in: foo.bar
     *
     * @param  string  ...$terms
     */
    protected function format(...$terms): string
    {
        return implode('.', $terms);
    }

    /**
     * Get the colors of the component.
     *
     * @param  string|string[]  $methods
     */
    protected function get(...$methods): array
    {
        $targets = Arr::wrap($methods);
        $targets = count($targets) === 1 ? $targets[0] : $targets;

        if (is_array($targets)) {
            $results = [];

            foreach ($targets as $target) {
                $results[] = $this->overrides[$target];
            }

            return $results;
        }

        return data_get($this->overrides, $targets);
    }

    /**
     * This method is used to determine which colors will be applied
     * to the component, whether they will be the custom colors in a
     * custom component class or whether they will be the default colors.
     */
    protected function setup(): void
    {
        $methods = collect((new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PRIVATE))
            ->map(fn (ReflectionMethod $method) => $method->getName())
            ->values()
            ->toArray();

        $data = $this->component->data();

        foreach ($methods as $method) {
            $original = $method;
            $method .= 'Color';

            if (! isset($data[$method]) || ! $data[$method] instanceof InvokableComponentVariable) {
                // If the colors was not overridden, we will get the default
                // colors of the methods of the class that is using this trait.
                $this->overrides[$original] = $this->{$original}();

                continue;
            }

            // We execute this as a closure because it will be
            // an instance of the InvokableComponentVariable
            $result = app()->call(fn () => $data[$method]()); // @phpstan-ignore-line

            $this->overrides[$original] = blank($result) ? null : $result;
        }
    }
}
