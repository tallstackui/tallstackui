<?php

namespace TallStackUi\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;
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

        // Trying to get the attribute from the current class, and if we
        // can't find it, we get the attribute from the parent class in
        // case of the deep personalization extending the original component.
        $attribute = rescue(
            /** @throws Exception */
            function () {
                $attribute = collect((new ReflectionClass($this))->getAttributes(SoftPersonalization::class))->first();

                // We need to throw an exception here to trigger the rescue.
                if (! $attribute) {
                    throw new Exception('No attribute found');
                }

                return $attribute;
            }, fn () => collect((new ReflectionClass(get_parent_class($this)))->getAttributes(SoftPersonalization::class))->first(), false);

        if (! $attribute || empty($attribute->getArguments())) {
            return [];
        }

        $bind = str($attribute->newInstance()->key())->remove('tallstack-ui::personalizations.')->value();

        // The strategy here is to preserve unique keys, prioritizing
        // merging what will come from the original classes with the
        // container bind for soft personalization.
        return Arr::only(
            array_merge($personalization = $this->personalization(),
                TallStackUi::personalize($bind)
                    ->instance()
                    ->toArray()
            ), array_keys($personalization)
        );
    }

    public function render(): Closure
    {
        // The main objective of this hook is to avoid filling
        // the constructor with formatting and things like that,
        // useful define style, size and position control.
        if (method_exists($this, 'prepare')) {
            $this->prepare();
        }

        return function (array $data) {
            return $this->output($this->blade()->with($this->compile($data)), $data);
        };
    }

    /** Proxy for the `wireable` method of the Facade */
    public function wireable(ComponentAttributeBag $attributes): ?WireDirective
    {
        return TallStackUi::blade()->wireable($attributes);
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
