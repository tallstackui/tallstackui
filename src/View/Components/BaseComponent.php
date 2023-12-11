<?php

namespace TallStackUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Foundation\Colors\ResolveColor;
use TallStackUi\Foundation\Contracts\MustReceiveColor;
use TallStackUi\Foundation\Contracts\MustReceiveConfiguration;
use TallStackUi\Foundation\Providers\ConfigurationProvider;
use Throwable;

abstract class BaseComponent extends Component
{
    abstract public function blade(): View;

    public function render(): Closure
    {
        return function (array $data) {
            return $this->output($this->blade()->with($this->compile($data)), $data);
        };
    }

    /** @throws Throwable */
    private function compile(array $data): array
    {
        if (method_exists($this, 'validate')) {
            $this->validate();
        }

        if (method_exists($this, 'setup')) {
            $this->setup();
        }

        if ($this instanceof MustReceiveColor) {
            $data = array_merge($data, ['colors' => [...ResolveColor::from($this)]]);
        }

        if ($this instanceof MustReceiveConfiguration) {
            $data = array_merge($data, ['configurations' => ConfigurationProvider::resolve($this)]);
        }

        return [...$data];
    }

    private function output(View $view, array $data): string
    {
        $config = collect(config('tallstackui.debug'));

        if (! $config->get('status', false) ||
            ! ($environment = $config->get('environments', [])) ||
            ! in_array(app()->environment(), $environment)
        ) {
            return $view->render();
        }

        $ignores = ['slot', 'trigger', 'content'];
        $lines = '';

        foreach (collect($data)
            ->filter(fn ($value) => ! is_array($value) && ! is_callable($value))
            ->filter(fn ($value, $key) => ! in_array($key, $ignores))
            ->toArray() as $key => $value) {
            $lines .= "<span class=\"text-white\">$key:</span> <span class=\"text-red-500\">$value</span>";
            $lines .= '<br>';
        }

        $html = $view->render();

        return <<<blade
            <x-tallstack-ui::debugger>
                $html
                <x-slot:code>
                    $lines              
                </x-slot:code>
            </x-tallstack-ui::debugger>
        blade;
    }
}
