<?php

namespace TallStackUi\View\Personalizations\Support\Colors\Traits;

use Illuminate\View\InvokableComponentVariable;
use ReflectionClass;
use ReflectionMethod;

trait OverrideColors
{
    protected array $overrides = [];

    /**
     * This method is used to determine which colors will be applied
     * to the component, whether they will be the custom colors in a
     * custom component class or whether they will be the default colors.
     */
    public function define(): void
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

            /** @var array|string|null $result */
            $result = $data[$method]();

            $this->overrides[$original] = blank($result) ? null : $result;
        }
    }

    public function override(...$methods): array
    {
        $methods = count($methods) === 1 ? $methods[0] : $methods;

        if (is_array($methods)) {
            $results = [];

            foreach ($methods as $method) {
                $results[] = $this->overrides[$method];
            }

            return $results;
        }

        return $this->overrides[$methods];
    }
}
