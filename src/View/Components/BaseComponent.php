<?php

namespace TallStackUi\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\Factory;
use Livewire\WireDirective;
use ReflectionAttribute;
use ReflectionClass;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Colors\ResolveColor;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\ResolveConfiguration;
use TallStackUi\Foundation\Support\BladeBindProperty;
use Throwable;

abstract class BaseComponent extends Component
{
    public function bind(
        ComponentAttributeBag $attributes,
        ViewErrorBag $errors,
        Factory $factory,
        bool $livewire = false
    ): array {
        return app(BladeBindProperty::class, [
            'attributes' => $attributes,
            'errors' => $errors,
            'factory' => $factory,
            'livewire' => $livewire,
        ])->data();
    }

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

        // The strategy here is to preserve unique keys, prioritizing
        // merging what will come from the original classes with the
        // container bind for soft personalization.
        return Arr::only(
            array_merge($personalization = $this->personalization(),
                TallStackUi::personalize(str_replace('tallstack-ui::personalizations.', '', $attribute->newInstance()->key()))
                    ->instance()
                    ->toArray()
            ), array_keys($personalization)
        );
    }

    public function render(): Closure
    {
        return function (array $data) {
            return $this->output($this->blade()->with($this->compile($data)), $data);
        };
    }

    /** Proxy for the `wireable` method of the Facade */ // TODO: probably deprecated
    public function wireable(ComponentAttributeBag $attributes, bool $livewire = false): ?WireDirective
    {
        return TallStackUi::blade($attributes, $livewire)->wire();
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
            ! in_array(app()->environment(), $environment) ||
            in_array($this->componentName, $config->get('ignore', []))
        ) {
            return $view;
        }

        // We need to start the debug mode filtering all
        // properties that should be skipped from debug mode.
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
