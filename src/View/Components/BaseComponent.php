<?php

namespace TallStackUi\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use ReflectionAttribute;
use ReflectionClass;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Colors\ResolveColor;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\Foundation\ResolveConfiguration;
use Throwable;

abstract class BaseComponent extends Component
{
    abstract public function blade(): View;

    public function classes(): array
    {
        if (! $this instanceof Personalization) {
            return [];
        }

        /** @var ReflectionAttribute $attribute */
        $attribute = collect((new ReflectionClass(get_parent_class($this)))->getAttributes(SoftPersonalization::class))->first();

        if (empty($attribute->getArguments())) {
            return [];
        }

        $bind = str($attribute->newInstance()->key())->remove('tallstack-ui::personalizations.')->value();
        $personalization = $this->personalization();

        //TODO.. doc this.
        return Arr::only(array_merge($personalization, TallStackUi::personalize($bind)->instance()->toArray()), array_keys($personalization));
    }

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

        if ($colors = ResolveColor::from($this)) {
            $data = array_merge($data, ['colors' => [...$colors]]);
        }

        if ($configurations = ResolveConfiguration::from($this)) {
            $data = array_merge($data, ['configurations' => [...$configurations]]);
        }

        return [...$data];
    }

    private function output(View $view, array $data): View|string
    {
        // When testing, we always display without debug mode.
        if (app()->runningUnitTests()) {
            return $view;
        }

        $config = collect(config('tallstackui.debug'));

        if (! $config->get('status', false) ||
            ! ($environment = $config->get('environments', [])) ||
            ! in_array(app()->environment(), $environment)
        ) {
            return $view;
        }

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
