<?php

namespace TallStackUi\Foundation\Support\Concerns\BaseComponent;

use Illuminate\Contracts\View\View;
use ReflectionAttribute;
use ReflectionClass;
use TallStackUi\Foundation\Attributes\SkipDebug;

trait ManagesOutput
{
    private function output(View $view, array $data): View|string
    {
        $config = collect(config('tallstackui'));
        $debug = collect($config->get('debug', []));

        // To improve performance, we disable debug mode when unit tests are running.
        if (app()->runningUnitTests() || ! $debug->get('status', false)) {
            return $view;
        }

        $ignores = array_merge($debug->get('ignore', []), ['floating']);

        // Ignoring when:
        // 1. Environment is not in list
        // 2. Component is in ignore list
        // 3. THIS class is in the ignore list
        if (! ($environment = $debug->get('environments', [])) ||
            ! in_array(app()->environment(), $environment) ||
            in_array($this->componentName, $ignores) ||
            in_array($this::class, $ignores)
        ) {
            return $view;
        }

        $data = collect($data)->filter(function (mixed $value, string $key) {
            $reflection = new ReflectionClass($this);

            // This strategy aims to filter only the properties that the
            // component has. Everything defined by the Laravel Component
            // will be filtered. Like: $blade, $attributes, $componentName, etc.
            return ! $reflection->hasProperty($key)
                    // Finally, we filter what should not be displayed using the SkipDebug attribute.
                || ! collect($reflection->getProperty($key)->getAttributes())->contains(fn (ReflectionAttribute $attribute) => $attribute->getName() === SkipDebug::class);
        })->toArray();

        $attributes = $this->view('tallstack-ui::components.debug.attributes', ['data' => $data])->render();

        return <<<blade
            <x-tallstack-ui::debug>
                {$view->render()}
                <x-slot:code>
                    $attributes
                </x-slot:code>
            </x-tallstack-ui::debug>
        blade;
    }
}
