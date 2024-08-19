<?php

namespace TallStackUi\Foundation\Colors\Traits;

use Illuminate\Support\Arr;
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

        $namespace = config('tallstackui.color_classes_namespace');
        $stubClassName = class_basename($this->reflect->parent()->name).'Colors';
        $usingCustomColors = class_exists($class = $namespace.'\\'.$stubClassName);

        foreach ($methods as $method) {
            $original = $method;
            $method .= 'Colors';

            if ($usingCustomColors) {
                $instance = new $class;

                $result = app()->call(fn () => $instance->{$method}());

                $this->overrides[$original] = blank($result) ? null : $result;
            } else {
                $this->overrides[$original] = $this->{$original}();
            }
        }
    }
}
