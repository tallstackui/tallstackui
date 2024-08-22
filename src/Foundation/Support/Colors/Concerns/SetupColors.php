<?php

namespace TallStackUi\Foundation\Support\Colors\Concerns;

use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionMethod;

use function Livewire\invade;

trait SetupColors
{
    /**
     * The color classes.
     */
    protected array $classes = [];

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
                $results[] = $this->classes[$target];
            }

            return $results;
        }

        return data_get($this->classes, $targets);
    }

    /**
     * Set up the colors for the component.
     */
    protected function setup(): void
    {
        // We use private visibility as a way to mark the methods.
        $methods = collect((new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PRIVATE))
            ->map(fn (ReflectionMethod $method) => $method->getName())
            ->values()
            ->toArray();

        $collect = __ts_class_collection(class_basename($this->reflect->parent()->name));
        $class = $collect->get('instance');

        foreach ($methods as $method) {
            $original = $method;
            $method .= 'Colors';

            if ($class) {
                // If for any unknown reason the method does not exist, we will use the default.
                if (! method_exists($class, $method)) {
                    $this->classes[$original] = $this->{$original}();

                    continue;
                }

                // We use invade to ensure that regardless of the method's visibility, we can obtain the value.
                $result = app()->call(fn () => invade($class)->{$method}($this->component));

                $this->classes[$original] = blank($result) ? null : $result;
            } else {
                $this->classes[$original] = $this->{$original}();
            }
        }
    }
}
