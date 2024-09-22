<?php

namespace TallStackUi\Foundation\Support\Colors\Concerns;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;

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
    protected function format(string ...$terms): string
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
        if (! $this->component instanceof Personalization) {
            return null;
        }

        if (! Arr::exists($this->component->personalization(), $index)) {
            throw new Exception("The personalization key [{$index}] does not exist.");
        }

        return $this->component->personalization()[$index] ?? null;
    }

    /**
     * Set up the colors for the component.
     *
     * @throws ReflectionException
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
