<?php

namespace TallStackUi\View\Personalizations\Support\Colors\Traits;

use Illuminate\View\InvokableComponentVariable;
use ReflectionClass;
use ReflectionMethod;

trait OverrideColors
{
    public function overrides(): array
    {
        $methods = collect((new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PRIVATE))
            ->map(fn (ReflectionMethod $method) => $method->getName())
            ->values()
            ->toArray();

        $data = $this->component->data();
        $results = [];

        foreach ($methods as $method) {
            $original = $method;

            // Suffix of the method name: backgroundColor, iconColor, etc.
            $method .= 'Color';

            if (! isset($data[$method]) || ! $data[$method] instanceof InvokableComponentVariable) {
                continue;
            }

            /** @var array|string|null $result */
            $result = $data[$method]();

            $results[$original] = blank($result) ? null : $result;
        }

        return $results;
    }
}
