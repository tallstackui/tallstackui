<?php

namespace TallStackUi\Foundation\Traits\BaseComponent;

use Exception;
use Illuminate\Support\Arr;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Components\ReflectComponent;

trait ManagesClasses
{
    /**
     * @throws Exception
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function classes(): array
    {
        if (! $this instanceof Personalization) {
            return [];
        }

        // The idea of this approach is to get the parent component. Since the component can
        // be personalized by the "deep" method, we need ReflectionApi to determine which
        // component is the parent to get its SoftPersonalization attribute. This way, all
        // personalization continue to work even when "deep" customization is in effect.
        $reflection = app(ReflectComponent::class, ['component' => static::class]);

        $attribute = $reflection->attribute();

        if (blank($attribute->getArguments())) {
            return [];
        }

        unset($this->attributes['personalize']);

        $soft = TallStackUi::personalize($attribute->newInstance()->key(prefix: false))
            ->instance()
            ->toArray();

        $scoped = [];

        if (isset($this->attributes['scope'])) {
            $scope = $this->attributes['scope'];

            unset($this->attributes['scope']);

            // We use rescue here as a way to ignore errors. If we don't find the personalization
            // in the container, we just don't apply it. This must be stated in the documentation.
            $scoped = rescue(fn () => app()->get(__ts_scope_container_key(__ts_search_component($reflection->parent()->getName()), $scope))->toArray(), [], false);

            if (filled($scoped)) {
                // Starting from v2, scope personalization creates a multidimensional array,
                // where the key is the scope name. Therefore, we need to get the scope name.
                $scoped = Arr::dot(data_get($scoped, $scope, $scoped));
            }
        }

        // If $scoped !== [] we merge the soft with scoped, but prioritize scoped.
        $merge = $scoped === []
            ? $soft
            : Arr::only(array_merge($soft, $scoped), array_keys($scoped));

        // Here we do a second merge, now with the original classes and the result
        // of the previous operation that will use the scope smooth prioritization
        // and customization settings. This is extremely necessary for cases where
        // $merge does not contain all the necessary keys in use by the component.
        $classes = Arr::only(array_merge($personalization = $this->personalization(), $merge), array_keys($personalization));

        // Lastly, we just pass the classes to a special hook method to allow
        // manipulation when necessary - a good example for this is the flat button.
        if (method_exists($this, 'manipulation')) {
            $classes = $this->manipulation($classes);
        }

        return $classes;
    }
}
