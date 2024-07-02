<?php

namespace TallStackUi\View\Components;

use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use ReflectionAttribute;
use ReflectionClass;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Colors\ResolveColor;
use TallStackUi\Foundation\Personalization\BuildScopePersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\ResolveConfiguration;
use TallStackUi\Foundation\Support\Blade\BladeBindProperty;
use Throwable;

abstract class BaseComponent extends Component
{
    public function bind(
        ComponentAttributeBag $attributes,
        ?ViewErrorBag $errors = null,
        bool $livewire = false
    ): array {
        return app(BladeBindProperty::class, [
            'attributes' => $attributes,
            'errors' => $errors,
            'invalidate' => $this->data()['invalidate'] ?? false,
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
                if ($attribute === null) {
                    throw new Exception('No attribute found');
                }

                return $attribute;
            }, fn () => collect((new ReflectionClass(get_parent_class($this)))->getAttributes(SoftPersonalization::class))->first(), false);

        if (! $attribute || empty($attribute->getArguments())) {
            return [];
        }

        $scope = app(BuildScopePersonalization::class, [
            'classes' => $personalization = $this->personalization(),
            'attributes' => $this->attributes['personalize'],
        ])();

        unset($this->attributes['personalize']); // We unset this because is useless after here.

        $soft = TallStackUi::personalize(str_replace('tallstack-ui::personalizations.', '', $attribute->newInstance()->key()))
            ->instance()
            ->toArray();

        $merged = empty($scope) ? $soft : Arr::only(array_merge($soft, $scope), array_keys($scope)); // We merge scope with soft personalization changes, but we prioritize scope changes.

        // Here we do a second merge, now with the original classes and
        // the result of the previous operation that will use scoped
        // prioritization and soft personalization definitions.
        $classes = Arr::only(array_merge($personalization, $merged), array_keys($personalization));

        // We use 'manipulation' method to manipulate default
        // classes that come from the personalization method.
        if (method_exists($this, 'manipulation')) {
            $classes = $this->manipulation($classes);
        }

        return $classes;
    }

    public function render(): Closure
    {
        return fn (array $data) => $this->output($this->blade()->with(array_merge($this->compile($data), [
            // This is an approach used to avoid having to "manually" check (isset($__livewire))
            // whether the component is being used within the Livewire context or not.
            'livewire' => isset($this->factory()->getShared()['__livewire']),
        ])), $data);
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
