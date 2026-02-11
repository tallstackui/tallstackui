<?php

namespace TallStackUi\Support\Colors\Concerns;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use TallStackUi\Attributes\ColorsThroughOf;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Miscellaneous\ReflectComponent;

use function Livewire\invade;

trait SetupColors
{
    /**
     * The color classes.
     */
    protected array $classes = [];

    /** @throws ReflectionException */
    public function __construct(protected Component $component, protected ReflectComponent $reflect)
    {
        $this->setup();
    }

    /**
     * Format the string to the data_get format (dot notation).
     */
    protected function format(?string ...$terms): string
    {
        return implode('.', $terms);
    }

    /**
     * Get the colors of the component.
     */
    protected function get(string ...$methods): array
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
     * Get default personalization contents of the component.
     *
     * @throws Exception
     */
    protected function personalization(string $index): ?string
    {
        if (! $this->component instanceof Customization) {
            return null;
        }

        if (! Arr::exists($this->component->customization(), $index)) {
            throw new Exception("The personalization key [{$index}] does not exist.");
        }

        return $this->component->customization()[$index] ?? null;
    }

    /**
     * Set up the colors for the component.
     *
     * @throws ReflectionException
     */
    protected function setup(): void
    {
        static $cache = [];

        $color = static::class;

        // We use private visibility as a way to mark the methods.
        if (! isset($cache[$color])) {
            $cache[$color] = array_values(array_map(
                fn (ReflectionMethod $method) => $method->getName(),
                (new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PRIVATE)
            ));
        }

        $methods = $cache[$color];

        /** @var ReflectionClass $parent */
        $parent = $this->reflect->parent();

        $attribute = $parent->getAttributes(ColorsThroughOf::class);
        $class = class_basename($attribute[0]->getArguments()[0]);

        $component = str_replace('Colors', '', $class);

        $collect = __ts_class_collection($component);
        $class = $collect['instance'] ?? null;

        foreach ($methods as $method) {
            $original = $method;
            $method .= 'Colors';

            if ($class) {
                if (! method_exists($class, $method)) {
                    $this->classes[$original] = $this->{$original}();

                    continue;
                }

                $result = invade($class)->{$method}($this->component);

                $this->classes[$original] = blank($result) ? null : $result;
            } else {
                $this->classes[$original] = $this->{$original}();
            }
        }
    }
}
