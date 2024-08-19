<?php

namespace TallStackUi\Foundation\Traits\BaseComponent;

use Illuminate\Contracts\View\View;
use ReflectionAttribute;
use ReflectionClass;
use TallStackUi\Foundation\Attributes\SkipDebug;

// TODO: refactor
trait ManagesOutput
{
    private function output(View $view, array $data): View|string
    {
        if (app()->runningUnitTests()) {
            return $view;
        }

        $config = collect(config('tallstackui'));
        $debug = collect($config->get('debug', []));

        $ignores = collect(array_merge($debug->get('ignore', []), ['floating']))
            ->map(function (string $component) use ($config) {
                $prefix = $config->get('prefix', '');

                if (blank($prefix) || str_starts_with($component, $prefix)) {
                    return $component;
                }

                return $prefix.$component;
            })
            ->toArray();

        if (! $debug->get('status', false) ||
            ! ($environment = $debug->get('environments', [])) ||
            ! in_array(app()->environment(), $environment) ||
            in_array($this->componentName, $ignores)
        ) {
            return $view;
        }

        $data = collect($data)->filter(function (mixed $value, string $key) {
            $reflection = new ReflectionClass($this);

            return ! $reflection->hasProperty($key) || ! collect(
                $reflection->getProperty($key)->getAttributes()
            )->contains(fn (ReflectionAttribute $attribute) => $attribute->getName() === SkipDebug::class);
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
