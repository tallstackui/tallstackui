<?php

namespace TallStackUi\Support\Concerns\BaseComponent;

use Closure;
use Exception;
use Illuminate\Support\Arr;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use TallStackUi\Attributes\SoftCustomization;
use TallStackUi\Components\Floating\Component as Floating;
use TallStackUi\Customization\Contracts\Customization;
use TallStackUi\Support\Miscellaneous\ReflectComponent;

trait ManagesClasses
{
    /**
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function classes(?Closure $callback = null): array
    {
        if (! $this instanceof Customization) {
            return [];
        }

        // The idea of this approach is to get the parent component. Since the component can
        // be customized by the "deep" method, we need ReflectionApi to determine which
        // component is the parent to get its SoftPersonalization attribute. This way, all
        // customization continues to work even when "deep" customization is in effect.
        $reflection = new ReflectComponent(static::class);

        $attribute = $reflection->attribute(SoftCustomization::class);

        if (blank($attribute?->getArguments())) {
            return [];
        }

        $factory = app($attribute->newInstance()->prefixed());
        $soft = $factory->toArray();

        $scoped = [];

        if (isset($this->attributes['scope'])) {
            $scope = $this->attributes['scope'];

            unset($this->attributes['scope']);

            $key = __ts_scope_container_key(__ts_search_component($reflection->parent()->getName()), $scope);

            if (app()->bound($key)) {
                $scoped = app()->get($key)->toArray();

                if (filled($scoped)) {
                    // Starting from v2, scope customization creates a multidimensional array,
                    // where the key is the scope name. Therefore, we need to get the scope name.
                    $scoped = Arr::dot(data_get($scoped, $scope, $scoped));
                }
            }
        }

        $merge = $scoped === [] ? $soft : Arr::only(array_merge($soft, $scoped), array_keys($scoped));

        // Here we do a second merge, now with the original classes and the result
        // of the previous operation that will use the scope smooth prioritization
        // and customization settings. This is extremely necessary for cases where
        // $merge does not contain all the necessary keys in use by the component.
        $classes = Arr::only(array_merge($customization = $this->customization(), $merge), array_keys($customization));

        // We just pass the classes to a special hook method to allow
        // manipulation when necessary - a good example of this is the flat button.
        if (method_exists($this, 'manipulation')) {
            $classes = $this->manipulation($classes);
        }

        if ($callback !== null) {
            $classes = $callback($classes);
        }

        if (__ts_global('square', static::class)) {
            foreach ($classes as $key => $value) {
                if (is_string($value)) {
                    $classes[$key] = preg_replace('/\s+/', ' ', trim((string) preg_replace('/(?:[\w-]+:)*rounded(?:-[a-z0-9]+)*/', '', $value)));
                }
            }
        }

        // The idea of this code is to nullable the floating.default customization to ensure
        // that soft customization can customize floating globally. This way we can ensure
        // that we can customize floating globally while also customizing the floating of a
        // specific component in a different way than the global customization.
        if (
            ! $this instanceof Floating
            && $soft === []
            && isset($classes['floating.default'])
        ) {
            $classes['floating.default'] = null;
        }

        return $classes;
    }
}
