<?php

namespace TallStackUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use ReflectionClass;
use TallStackUi\Foundation\Colors\ColorSource;
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
            return $this->blade()
                ->with($this->compile($data));
        };
    }

    private function colors(): array
    {
        $attribute = (new ReflectionClass($this))->getAttributes(ColorSource::class)[0];

        return app(collect($attribute->getArguments())->first(), ['component' => $this])();
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
            $data = array_merge($data, ['colors' => [...$this->colors()]]);
        }

        if ($this instanceof MustReceiveConfiguration) {
            $data = array_merge($data, ['configurations' => ConfigurationProvider::resolve($this)]);
        }

        return [...$data];
    }
}
