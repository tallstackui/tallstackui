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
            $content = $this->blade()->with($this->compile($data));
            // filter $data ignoring all arrays and invokable component variables
            $data = collect($data)
                ->filter(fn ($value) => ! is_array($value) && ! is_callable($value))
                ->filter(fn ($value, $key) => $key !== 'slot' && $key !== 'trigger' && $key !== 'content')
                ->toArray();

            $lines = '';

            foreach ($data as $key => $value) {
                $lines .= "<span class=\"text-white\">$key:</span> <span class=\"text-red-500\">$value</span>";
                $lines .= '<br>';
            }

            return <<<blade
                <x-tallstack-ui::debugger>
                    $content
                    <x-slot:code>
                        $lines              
                    </x-slot:code>
                </x-tallstack-ui::debugger>
            blade;
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
}
